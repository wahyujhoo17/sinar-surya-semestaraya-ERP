<x-app-layout>
    <div class="max-w-6xl mx-auto py-6 sm:px-6 lg:px-8">
        @include('keuangan.management_pajak.partials.alerts')

        {{-- Header --}}
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-10 w-1 rounded-full mr-3">
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                                Detail Laporan Pajak
                            </h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ $laporanPajak->nomor }} - {{ $laporanPajak->formatted_jenis }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    @if ($laporanPajak->status === 'draft')
                        {{-- Edit Button --}}
                        <a href="{{ route('keuangan.management-pajak.edit', $laporanPajak->id) }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 border border-transparent rounded-lg font-medium text-xs text-white uppercase tracking-widest focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>

                        {{-- Finalize Button --}}
                        <form action="{{ route('keuangan.management-pajak.finalize', $laporanPajak->id) }}"
                            method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                onclick="return confirm('Apakah Anda yakin ingin memfinalisasi laporan pajak ini? Setelah difinalisasi, laporan tidak dapat diubah lagi.')"
                                class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 border border-transparent rounded-lg font-medium text-xs text-white uppercase tracking-widest focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Finalisasi
                            </button>
                        </form>
                    @endif

                    {{-- PDF Export Button --}}
                    <a href="{{ route('keuangan.management-pajak.export-pdf', $laporanPajak->id) }}"
                        class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 border border-transparent rounded-lg font-medium text-xs text-white uppercase tracking-widest focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download PDF
                    </a>

                    {{-- Back Button --}}
                    <a href="{{ route('keuangan.management-pajak.index') }}"
                        class="inline-flex items-center justify-center px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 border border-transparent rounded-lg font-medium text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest focus:bg-gray-400 dark:focus:bg-gray-500 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Information Card --}}
            <div class="lg:col-span-2">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Laporan Pajak</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                    Nomor Laporan
                                </label>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $laporanPajak->nomor }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                    Jenis Pajak
                                </label>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium
                                    @if ($laporanPajak->jenis_pajak === 'ppn_keluaran') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @elseif($laporanPajak->jenis_pajak === 'ppn_masukan') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                    @elseif(str_starts_with($laporanPajak->jenis_pajak, 'pph')) bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 @endif">
                                    {{ $laporanPajak->formatted_jenis }}
                                </span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                    Tanggal Laporan
                                </label>
                                <p class="text-sm text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($laporanPajak->tanggal)->format('d F Y') }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                    Status
                                </label>
                                @if ($laporanPajak->status === 'draft')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                        <svg class="mr-1.5 h-2 w-2 fill-current" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Draft
                                    </span>
                                @elseif($laporanPajak->status === 'final')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        <svg class="mr-1.5 h-2 w-2 fill-current" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Final
                                    </span>
                                @endif
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                    Periode Awal
                                </label>
                                <p class="text-sm text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($laporanPajak->periode_awal)->format('d F Y') }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                    Periode Akhir
                                </label>
                                <p class="text-sm text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($laporanPajak->periode_akhir)->format('d F Y') }}
                                </p>
                            </div>

                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                    Nilai Pajak
                                </label>
                                <p class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                                    Rp
                                    {{ number_format($laporanPajak->jumlah_pajak ?? $laporanPajak->nilai, 0, ',', '.') }}
                                </p>
                            </div>

                            @if ($laporanPajak->keterangan)
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        Keterangan
                                    </label>
                                    <p
                                        class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                        {{ $laporanPajak->keterangan }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Summary Card --}}
            <div class="lg:col-span-1">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Ringkasan</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Periode:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($laporanPajak->periode_awal)->diffInDays(\Carbon\Carbon::parse($laporanPajak->periode_akhir)) + 1 }}
                                hari
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Transaksi Terkait:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $relatedTransactions->count() }} transaksi
                            </span>
                        </div>

                        @if ($relatedTransactions->count() > 0)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Total Base Amount:</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    Rp {{ number_format($relatedTransactions->sum('base_amount'), 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Total Tax Amount:</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    Rp {{ number_format($relatedTransactions->sum('tax_amount'), 0, ',', '.') }}
                                </span>
                            </div>
                        @endif

                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat oleh:</span>
                                <span class="text-sm text-gray-900 dark:text-white">
                                    {{ $laporanPajak->user->name ?? 'N/A' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Tanggal dibuat:</span>
                                <span class="text-sm text-gray-900 dark:text-white">
                                    {{ $laporanPajak->created_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                            @if ($laporanPajak->updated_at != $laporanPajak->created_at)
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Terakhir diubah:</span>
                                    <span class="text-sm text-gray-900 dark:text-white">
                                        {{ $laporanPajak->updated_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Related Transactions --}}
        @if ($relatedTransactions->count() > 0)
            <div class="mt-8">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            Transaksi Terkait ({{ $relatedTransactions->count() }})
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Transaksi yang mempengaruhi perhitungan pajak untuk periode ini
                        </p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Tipe
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Nomor
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Partner
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Base Amount
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Tax Rate
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Tax Amount
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($relatedTransactions as $transaction)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if ($transaction['type'] === 'Sales Order') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                                @else bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 @endif">
                                                {{ $transaction['type'] }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $transaction['nomor'] }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($transaction['tanggal'])->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $transaction['partner'] }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right">
                                            Rp {{ number_format($transaction['base_amount'], 0, ',', '.') }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right">
                                            {{ $transaction['tax_rate'] }}%
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white text-right">
                                            Rp {{ number_format($transaction['tax_amount'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <td colspan="4"
                                        class="px-6 py-3 text-left text-sm font-medium text-gray-900 dark:text-white">
                                        Total
                                    </td>
                                    <td class="px-6 py-3 text-right text-sm font-medium text-gray-900 dark:text-white">
                                        Rp {{ number_format($relatedTransactions->sum('base_amount'), 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-3 text-right text-sm font-medium text-gray-900 dark:text-white">
                                        -
                                    </td>
                                    <td class="px-6 py-3 text-right text-sm font-medium text-gray-900 dark:text-white">
                                        Rp {{ number_format($relatedTransactions->sum('tax_amount'), 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
