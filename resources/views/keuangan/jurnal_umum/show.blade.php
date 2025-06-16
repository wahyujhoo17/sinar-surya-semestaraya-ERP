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
                <a href="{{ route('keuangan.jurnal-umum.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                <a href="{{ route('keuangan.jurnal-umum.edit', $jurnal->id) }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <button type="button" onclick="confirmDelete('{{ $jurnal->id }}')"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus
                </button>
                <form id="delete-form-{{ $jurnal->id }}"
                    action="{{ route('keuangan.jurnal-umum.destroy', $jurnal->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
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

    <script>
        function confirmDelete(id) {
            if (confirm(
                    'Apakah Anda yakin ingin menghapus jurnal ini? Tindakan ini akan menghapus seluruh entri jurnal dengan nomor referensi yang sama dan memulihkan saldo kas dan rekening bank yang terkait.'
                    )) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
</x-app-layout>
