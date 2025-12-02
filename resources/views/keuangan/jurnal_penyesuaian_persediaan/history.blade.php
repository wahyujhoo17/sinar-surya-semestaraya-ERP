<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="'History Kalibrasi Persediaan'">
    <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-indigo-600 dark:text-indigo-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    History Kalibrasi Persediaan
                </h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Riwayat jurnal penyesuaian persediaan yang telah dilakukan
                </p>
            </div>
            <a href="{{ route('keuangan.jurnal-penyesuaian-persediaan.index') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Kalibrasi
            </a>
        </div>

        {{-- Summary Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Penyesuaian</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $jurnals->count() }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-500 opacity-50" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Terakhir Update</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white mt-2">
                            @if ($jurnals->isNotEmpty())
                                {{ $jurnals->first()->first()->first()->tanggal->format('d M Y') }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-500 opacity-50" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Status</p>
                        <p class="text-lg font-semibold text-green-600 dark:text-green-400 mt-2">
                            Semua Diposting
                        </p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-500 opacity-50" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- History Table --}}
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Riwayat Jurnal Penyesuaian Persediaan
                </h3>
            </div>

            @forelse($jurnals as $noReferensi => $entries)
                @php
                    $firstEntry = $entries->first();
                    $totalDebit = $entries->sum('debit');
                    $totalKredit = $entries->sum('kredit');
                @endphp

                <div class="border-b border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/30">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <h4 class="text-base font-semibold text-gray-900 dark:text-white">
                                    {{ $noReferensi }}
                                </h4>
                                <div class="flex items-center gap-4 mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    <span class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $firstEntry->tanggal->format('d M Y') }}
                                    </span>
                                    <span class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        {{ $firstEntry->user->name ?? '-' }}
                                    </span>
                                    <span
                                        class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        ✓ Posted
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Nilai Penyesuaian</p>
                                <p class="text-xl font-bold text-indigo-600 dark:text-indigo-400">
                                    Rp {{ number_format($totalDebit, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-3">
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                <strong>Keterangan:</strong> {{ $firstEntry->keterangan }}
                            </p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-100 dark:bg-gray-900/50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Akun
                                    </th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Debit
                                    </th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Kredit
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($entries as $entry)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <td class="px-6 py-4 text-sm">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-8 w-8 rounded-full {{ $entry->debit > 0 ? 'bg-green-100 dark:bg-green-900' : 'bg-red-100 dark:bg-red-900' }} flex items-center justify-center mr-3">
                                                    <span
                                                        class="text-xs font-semibold {{ $entry->debit > 0 ? 'text-green-800 dark:text-green-200' : 'text-red-800 dark:text-red-200' }}">
                                                        {{ $entry->debit > 0 ? 'D' : 'K' }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-900 dark:text-white">
                                                        {{ $entry->akun->nama }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $entry->akun->kode }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                            @if ($entry->debit > 0)
                                                <span class="font-semibold text-green-600 dark:text-green-400">
                                                    Rp {{ number_format($entry->debit, 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                            @if ($entry->kredit > 0)
                                                <span class="font-semibold text-red-600 dark:text-red-400">
                                                    Rp {{ number_format($entry->kredit, 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    <td class="px-6 py-3 text-sm font-bold text-gray-900 dark:text-white">
                                        Total
                                    </td>
                                    <td class="px-6 py-3 text-sm font-bold text-right text-gray-900 dark:text-white">
                                        Rp {{ number_format($totalDebit, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-3 text-sm font-bold text-right text-gray-900 dark:text-white">
                                        Rp {{ number_format($totalKredit, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-lg font-medium">Belum ada history kalibrasi persediaan</p>
                    <p class="text-sm mt-1">Lakukan kalibrasi pertama untuk melihat history</p>
                    <a href="{{ route('keuangan.jurnal-penyesuaian-persediaan.index') }}"
                        class="inline-flex items-center mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Mulai Kalibrasi
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Info Box --}}
        <div class="mt-8 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
            <div class="flex">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 text-blue-600 dark:text-blue-400 mr-3 flex-shrink-0" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-300 mb-2">Catatan:</h4>
                    <ul class="text-sm text-blue-800 dark:text-blue-400 space-y-1 list-disc list-inside">
                        <li>Semua jurnal penyesuaian persediaan langsung diposting (tidak bisa di-unpost)</li>
                        <li>Untuk melihat detail lengkap, cek menu <strong>Jurnal Umum</strong> dengan filter jenis
                            "Penyesuaian Persediaan"</li>
                        <li>History ini berguna untuk audit trail dan tracking perubahan nilai persediaan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
