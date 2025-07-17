<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Overview Header --}}
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="flex items-center">
                <div class="bg-primary-100 dark:bg-primary-900/30 p-3 rounded-lg mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary-600 dark:text-primary-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        Detail Jurnal Umum
                    </h1>
                    <p class="mt-1 text-gray-600 dark:text-gray-400 flex items-center">
                        <span
                            class="font-medium text-primary-600 dark:text-primary-400 mr-2">{{ $jurnal->no_referensi }}</span>
                        |
                        <span class="ml-2">{{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d F Y') }}</span>
                    </p>
                </div>
            </div>

            <div class="mt-4 md:mt-0 flex flex-wrap gap-2">
                {{-- Status Badge --}}
                <div class="flex items-center">
                    @if ($jurnal->is_posted)
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
                <a href="{{ route('keuangan.jurnal-umum.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>

                @if (!$jurnal->is_posted)
                    {{-- Post Button --}}
                    <button type="button"
                        class="post-unpost-btn inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"
                        data-action="post" data-no-referensi="{{ $jurnal->no_referensi }}"
                        data-form-action="{{ route('keuangan.jurnal-umum.post') }}">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Post Jurnal
                    </button>

                    {{-- Edit Button --}}
                    <a href="{{ route('keuangan.jurnal-umum.edit', $jurnal->id) }}"
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
                        data-action="unpost" data-no-referensi="{{ $jurnal->no_referensi }}"
                        data-form-action="{{ route('keuangan.jurnal-umum.unpost') }}">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Unpost Jurnal
                    </button>
                @endif

                {{-- Delete Button --}}
                @if (!$jurnal->is_posted)
                    <button type="button" data-delete-id="{{ $jurnal->id }}"
                        data-no-referensi="{{ $jurnal->no_referensi }}"
                        data-tanggal="{{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d/m/Y') }}"
                        class="btn-delete inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
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
                                        {{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d F Y') }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 flex-shrink-0 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-primary-600 dark:text-primary-400" fill="none"
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
                                        {{ $jurnal->no_referensi }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 flex-shrink-0 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-primary-600 dark:text-primary-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat
                                        Oleh</span>
                                    <span class="mt-1 block text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $jurnal->user->name ?? 'Sistem' }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-start pt-2">
                                <div
                                    class="w-10 h-10 flex-shrink-0 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-primary-600 dark:text-primary-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <span
                                        class="block text-sm font-medium text-gray-500 dark:text-gray-400">Keterangan</span>
                                    <span class="mt-1 block text-base text-gray-900 dark:text-white">
                                        {{ $jurnal->keterangan }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Calculate totals for entire jurnal -->
                        @php
                            $totalDebit = 0;
                            $totalKredit = 0;
                            foreach ($relatedJurnals as $item) {
                                $totalDebit += $item->debit;
                                $totalKredit += $item->kredit;
                            }
                            $isBalanced = abs($totalDebit - $totalKredit) < 0.01;
                        @endphp

                        <!-- Status Jurnal -->
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Jurnal</span>
                                <span
                                    class="px-3 py-1 text-xs font-medium rounded-full {{ $isBalanced ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                    {{ $isBalanced ? 'Seimbang' : 'Tidak Seimbang' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Journal Entries List Card --}}
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden h-full">
                    <div
                        class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-750 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 text-primary-600 dark:text-primary-400 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Detail Entri Jurnal</h3>
                        </div>
                        <div>
                            <span
                                class="bg-primary-50 dark:bg-primary-900/30 px-2 py-1 rounded-md text-primary-700 dark:text-primary-400 text-sm">
                                {{ count($relatedJurnals) }} entri
                            </span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead
                                class="bg-gradient-to-r from-gray-100 to-gray-50 dark:from-gray-700 dark:to-gray-800 border-b border-gray-200 dark:border-gray-600">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                            Kode Akun
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Nama Akun
                                        </div>
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
                                @foreach ($relatedJurnals as $item)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="font-mono text-sm text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                                {{ $item->akun->kode }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            {{ $item->akun->nama }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                            @if ($item->debit > 0)
                                                <span class="text-emerald-600 dark:text-emerald-400 font-medium">
                                                    {{ number_format($item->debit, 2, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-600">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                            @if ($item->kredit > 0)
                                                <span class="text-blue-600 dark:text-blue-400 font-medium">
                                                    {{ number_format($item->kredit, 2, ',', '.') }}
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
                </div>
            </div>
        </div>

        <!-- Footer Section -->
        <div class="mt-6 text-right text-sm text-gray-500 dark:text-gray-400">
            <p>Terakhir diperbarui: {{ $jurnal->updated_at->format('d F Y H:i') }}</p>
        </div>
    </div>

    {{-- Custom Delete Confirmation Modal --}}
    <div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
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
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                Konfirmasi Hapus Jurnal
                            </h3>
                            <div class="mt-2">
                                <div class="text-sm text-gray-500 dark:text-gray-400" id="modal-message">
                                    <!-- Dynamic message will be inserted here -->
                                </div>
                                <div
                                    class="mt-3 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-md border border-yellow-200 dark:border-yellow-800">
                                    <div class="flex">
                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                                <strong>Perhatian:</strong> Jika jurnal sudah diposting, sistem akan
                                                otomatis membatalkan posting terlebih dahulu untuk memulihkan saldo kas
                                                dan rekening bank, kemudian menghapus seluruh entri jurnal.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="confirmDeleteBtn"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        Ya, Hapus Jurnal
                    </button>
                    <button type="button" id="cancelDeleteBtn"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        Batal
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

    <script>
        // Global variables for modal functionality
        let currentDeleteId = null;

        // Initialize modal event listeners when page loads
        document.addEventListener('DOMContentLoaded', function() {
            setupModalEventListeners();
        });

        // Event delegation for delete button
        document.addEventListener('click', function(e) {
            const deleteButton = e.target.closest('.btn-delete, [data-delete-id]');
            if (deleteButton) {
                e.preventDefault();
                e.stopPropagation();

                const id = deleteButton.getAttribute('data-delete-id');
                const noReferensi = deleteButton.getAttribute('data-no-referensi') || 'N/A';
                const tanggal = deleteButton.getAttribute('data-tanggal') || 'N/A';

                // console.log('Delete button clicked for ID:', id);
                showDeleteModal(id, noReferensi, tanggal);
            }
        });

        // Event delegation for post/unpost buttons
        document.addEventListener('click', function(e) {
            const postUnpostButton = e.target.closest('.post-unpost-btn');
            if (postUnpostButton) {
                e.preventDefault();
                e.stopPropagation();

                const action = postUnpostButton.getAttribute('data-action');
                const noReferensi = postUnpostButton.getAttribute('data-no-referensi') || 'N/A';
                const formAction = postUnpostButton.getAttribute('data-form-action') || '';

                // console.log('Post/Unpost button clicked:', action, noReferensi);
                showPostUnpostModal(action, noReferensi, formAction);
            }
        });

        function setupModalEventListeners() {
            // Delete modal event listeners
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', function() {
                    if (currentDeleteId) {
                        // console.log('Confirm delete clicked for ID:', currentDeleteId);
                        executeDelete(currentDeleteId);
                    } else {
                        console.error('No currentDeleteId set');
                    }
                    hideDeleteModal();
                });
            }

            const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
            if (cancelDeleteBtn) {
                cancelDeleteBtn.addEventListener('click', function() {
                    // console.log('Delete cancelled');
                    hideDeleteModal();
                });
            }

            const deleteModal = document.getElementById('deleteModal');
            if (deleteModal) {
                deleteModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        // console.log('Modal closed by clicking outside');
                        hideDeleteModal();
                    }
                });
            }

            // Post/Unpost modal event listeners
            const confirmPostBtn = document.getElementById('confirmPostBtn');
            if (confirmPostBtn) {
                confirmPostBtn.addEventListener('click', function() {
                    const formAction = this.getAttribute('data-form-action');
                    const noReferensi = this.getAttribute('data-no-referensi');

                    if (formAction && noReferensi) {
                        executePostUnpost(formAction, noReferensi);
                    }
                    hidePostUnpostModal();
                });
            }

            const cancelPostBtn = document.getElementById('cancelPostBtn');
            if (cancelPostBtn) {
                cancelPostBtn.addEventListener('click', function() {
                    hidePostUnpostModal();
                });
            }

            const postModal = document.getElementById('postModal');
            if (postModal) {
                postModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        hidePostUnpostModal();
                    }
                });
            }
        }

        // Safe DOM element getter
        function safeGetElement(id) {
            const element = document.getElementById(id);
            if (!element) {
                console.warn(`Element with ID '${id}' not found`);
            }
            return element;
        }

        // Show post/unpost modal
        function showPostUnpostModal(action, noReferensi, formAction) {
            const modal = safeGetElement('postModal');
            const titleElement = safeGetElement('postModalTitle');
            const messageElement = safeGetElement('postModalMessage');
            const warningElement = safeGetElement('postModalWarning');
            const iconElement = safeGetElement('postModalIcon');
            const confirmBtn = safeGetElement('confirmPostBtn');

            if (!modal || !confirmBtn) return;

            // Set data attributes for form submission
            confirmBtn.setAttribute('data-form-action', formAction);
            confirmBtn.setAttribute('data-no-referensi', noReferensi);

            if (action === 'post') {
                // Post configuration
                if (titleElement) titleElement.textContent = 'Konfirmasi Post Jurnal';
                if (messageElement) messageElement.textContent =
                    `Apakah Anda yakin ingin memposting jurnal ${noReferensi}?`;
                if (warningElement) {
                    warningElement.innerHTML = `
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-yellow-800 dark:text-yellow-200">Jurnal yang sudah diposting tidak dapat diedit lagi.</span>
                        </div>
                    `;
                    warningElement.className =
                        'mt-3 p-3 rounded-md border border-yellow-200 bg-yellow-50 dark:border-yellow-700 dark:bg-yellow-800/20';
                }
                if (iconElement) {
                    iconElement.innerHTML = `
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    `;
                    iconElement.className =
                        'mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/30 sm:mx-0 sm:h-10 sm:w-10';
                }
                confirmBtn.textContent = 'Ya, Post Jurnal';
                confirmBtn.className =
                    'w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 hover:bg-green-700 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200';
            } else if (action === 'unpost') {
                // Unpost configuration
                if (titleElement) titleElement.textContent = 'Konfirmasi Unpost Jurnal';
                if (messageElement) messageElement.textContent =
                    `Apakah Anda yakin ingin membatalkan posting jurnal ${noReferensi}?`;
                if (warningElement) {
                    warningElement.innerHTML = `
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-blue-800 dark:text-blue-200">Jurnal akan kembali ke status draft dan dapat diedit.</span>
                        </div>
                    `;
                    warningElement.className =
                        'mt-3 p-3 rounded-md border border-blue-200 bg-blue-50 dark:border-blue-700 dark:bg-blue-800/20';
                }
                if (iconElement) {
                    iconElement.innerHTML = `
                        <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    `;
                    iconElement.className =
                        'mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-orange-100 dark:bg-orange-900/30 sm:mx-0 sm:h-10 sm:w-10';
                }
                confirmBtn.textContent = 'Ya, Unpost Jurnal';
                confirmBtn.className =
                    'w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-orange-600 hover:bg-orange-700 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200';
            }

            // Show modal
            modal.classList.remove('hidden');

            // Focus on cancel button for better UX
            setTimeout(() => {
                const cancelBtn = safeGetElement('cancelPostBtn');
                if (cancelBtn) cancelBtn.focus();
            }, 100);
        }

        // Hide post/unpost modal
        function hidePostUnpostModal() {
            const modal = safeGetElement('postModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        // Execute post/unpost action
        function executePostUnpost(formAction, noReferensi) {
            const confirmBtn = safeGetElement('confirmPostBtn');

            if (confirmBtn) {
                confirmBtn.disabled = true;
                confirmBtn.textContent = 'Memproses...';
            }

            // Create and submit form dynamically
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = formAction;

            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            // Add no_referensi field
            const noReferensiInput = document.createElement('input');
            noReferensiInput.type = 'hidden';
            noReferensiInput.name = 'no_referensi';
            noReferensiInput.value = noReferensi;
            form.appendChild(noReferensiInput);

            // Add to document and submit
            document.body.appendChild(form);
            form.submit();
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const deleteModal = document.getElementById('deleteModal');
                const postModal = document.getElementById('postModal');

                if (deleteModal && !deleteModal.classList.contains('hidden')) {
                    // console.log('Delete modal closed with Escape key');
                    hideDeleteModal();
                } else if (postModal && !postModal.classList.contains('hidden')) {
                    // console.log('Post modal closed with Escape key');
                    hidePostUnpostModal();
                }
            }
        });

        function showDeleteModal(id, noReferensi = 'N/A', tanggal = 'N/A') {
            currentDeleteId = id;

            // Update modal message with detailed information
            const modal = document.getElementById('deleteModal');
            const message = document.getElementById('modal-message');
            message.innerHTML = `
                <div class="space-y-2">
                    <p>Apakah Anda yakin ingin menghapus jurnal berikut?</p>
                    <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md text-sm">
                        <div class="grid grid-cols-2 gap-2">
                            <div><strong>ID:</strong> ${id}</div>
                            <div><strong>Tanggal:</strong> ${tanggal}</div>
                            <div class="col-span-2"><strong>No. Referensi:</strong> ${noReferensi}</div>
                        </div>
                    </div>
                    <p class="text-red-600 dark:text-red-400 font-medium">Tindakan ini tidak dapat dibatalkan!</p>
                </div>
            `;

            // Show modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling

            // Focus on cancel button for accessibility
            document.getElementById('cancelDeleteBtn').focus();
        }

        function hideDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Restore scrolling
            currentDeleteId = null;

            // Reset confirm button state
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            confirmBtn.disabled = false;
            confirmBtn.textContent = 'Ya, Hapus Jurnal';
        }

        function executeDelete(id) {
            // console.log('Attempting to delete journal with ID:', id);

            // First try to find the form
            const form = document.getElementById('delete-form-' + id);

            if (form) {
                // console.log('Form found, submitting...');

                // Disable the confirm button to prevent double submission
                const confirmBtn = document.getElementById('confirmDeleteBtn');
                confirmBtn.disabled = true;
                confirmBtn.textContent = 'Menghapus...';

                // Submit the form
                form.submit();
            } else {
                // console.error('Form not found with id: delete-form-' + id);

                // Try alternative method: create and submit form dynamically
                // console.log('Trying alternative method: creating form dynamically');

                const dynamicForm = document.createElement('form');
                dynamicForm.method = 'POST';

                // Build the URL manually using the current page URL
                const baseUrl = window.location.origin + '/keuangan/jurnal-umum/' + id;
                dynamicForm.action = baseUrl;

                // Add CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                dynamicForm.appendChild(csrfInput);

                // Add method field for DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                dynamicForm.appendChild(methodInput);

                // Add to document and submit
                document.body.appendChild(dynamicForm);
                dynamicForm.submit();

                // console.log('Dynamic form created and submitted');
            }
        }

        // Legacy functions for backward compatibility (redirects to modal system)
        function confirmDelete(id) {
            // Redirect to new modal system
            const button = document.querySelector(`[data-delete-id="${id}"]`);
            if (button) {
                button.click();
            } else {
                // Fallback for direct calls
                showDeleteModal(id);
            }
        }

        function confirmPost(noReferensi) {
            // Redirect to modal system
            const button = document.querySelector(
                `.post-unpost-btn[data-action="post"][data-no-referensi="${noReferensi}"]`);
            if (button) {
                button.click();
            } else {
                console.warn('Post button not found for noReferensi:', noReferensi);
            }
        }

        function confirmUnpost(noReferensi) {
            // Redirect to modal system  
            const button = document.querySelector(
                `.post-unpost-btn[data-action="unpost"][data-no-referensi="${noReferensi}"]`);
            if (button) {
                button.click();
            } else {
                console.warn('Unpost button not found for noReferensi:', noReferensi);
            }
        }
    </script>
</x-app-layout>
