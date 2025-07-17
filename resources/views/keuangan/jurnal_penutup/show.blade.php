<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="'Detail Jurnal Penutup'">
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2 text-red-600 dark:text-red-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Detail Jurnal Penutup
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    {{ $jurnalPenutup->no_referensi }} -
                    {{ \Carbon\Carbon::parse($jurnalPenutup->tanggal)->format('d F Y') }}
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-2">
                @if (!$jurnalPenutup->is_posted)
                    <a href="{{ route('keuangan.jurnal-penutup.edit', $jurnalPenutup->no_referensi) }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                @endif
                <a href="{{ route('keuangan.jurnal-penutup.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Summary Cards --}}
            <div class="lg:col-span-3 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    {{-- Total Entries --}}
                    <div
                        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm transform transition-all duration-300 hover:shadow-md hover:scale-105">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center transition-all duration-300">
                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Entri</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                        {{ $jurnalPenutup->details->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Total Debit --}}
                    <div
                        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm transform transition-all duration-300 hover:shadow-md hover:scale-105">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center transition-all duration-300">
                                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Debit</p>
                                    <p class="text-lg font-semibold text-green-600 dark:text-green-400">
                                        Rp
                                        {{ number_format($jurnalPenutup->total_debit ?? $jurnalPenutup->details->sum('debit'), 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Total Kredit --}}
                    <div
                        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm transform transition-all duration-300 hover:shadow-md hover:scale-105">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center transition-all duration-300">
                                        <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Kredit</p>
                                    <p class="text-lg font-semibold text-red-600 dark:text-red-400">
                                        Rp
                                        {{ number_format($jurnalPenutup->total_kredit ?? $jurnalPenutup->details->sum('kredit'), 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Balance Status --}}
                    <div
                        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm transform transition-all duration-300 hover:shadow-md hover:scale-105">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    @php
                                        $totalDebit =
                                            $jurnalPenutup->total_debit ?? $jurnalPenutup->details->sum('debit');
                                        $totalKredit =
                                            $jurnalPenutup->total_kredit ?? $jurnalPenutup->details->sum('kredit');
                                        $isBalanced = $totalDebit == $totalKredit;
                                    @endphp
                                    <div
                                        class="w-8 h-8 {{ $isBalanced ? 'bg-green-100 dark:bg-green-900' : 'bg-red-100 dark:bg-red-900' }} rounded-full flex items-center justify-center transition-all duration-300">
                                        @if ($isBalanced)
                                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                    <p
                                        class="text-lg font-semibold {{ $isBalanced ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $isBalanced ? 'Seimbang' : 'Tidak Seimbang' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Jurnal Info --}}
                <div
                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm transform transition-all duration-300 hover:shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Informasi Jurnal
                        </h3>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">No. Referensi</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-medium">
                                    {{ $jurnalPenutup->no_referensi }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($jurnalPenutup->tanggal)->format('d F Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Periode Akuntansi</dt>
                                <dd class="mt-1">
                                    @if ($jurnalPenutup->periodeAkuntansi)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4h8a2 2 0 012 2v8a2 2 0 01-2 2H6a2 2 0 01-2-2v-8a2 2 0 012-2z" />
                                            </svg>
                                            {{ $jurnalPenutup->periodeAkuntansi->nama }}
                                        </span>
                                    @else
                                        <div class="space-y-2">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Periode belum ditetapkan
                                            </span>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                <span class="flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Gunakan command: <code
                                                        class="bg-gray-100 dark:bg-gray-700 px-1 rounded">php artisan
                                                        periode:assign</code>
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-medium">
                                    Rp
                                    {{ number_format($jurnalPenutup->total_debit ?? $jurnalPenutup->details->sum('debit'), 0, ',', '.') }}
                                </dd>
                            </div>
                            <div class="md:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Keterangan</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $jurnalPenutup->keterangan }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- Journal Details --}}
                <div
                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm transform transition-all duration-300 hover:shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Detail Jurnal
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Akun
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Keterangan
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Debit
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Kredit
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($jurnalPenutup->details as $detail)
                                    <tr
                                        class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 transform hover:scale-[1.01]">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($detail->akun)
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 w-10 h-10">
                                                        <div
                                                            class="w-10 h-10 bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-800 dark:to-indigo-800 rounded-lg flex items-center justify-center">
                                                            <span
                                                                class="text-sm font-bold text-blue-600 dark:text-blue-400">
                                                                {{ substr($detail->akun->kode, 0, 2) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div
                                                            class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                            {{ $detail->akun->kode }} - {{ $detail->akun->nama }}
                                                        </div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                                            <span
                                                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                                                {{ $detail->akun->kategori ?? ($detail->akun->jenis ?? 'Akun') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 w-10 h-10">
                                                        <div
                                                            class="w-10 h-10 bg-gradient-to-br from-red-100 to-pink-100 dark:from-red-800 dark:to-pink-800 rounded-lg flex items-center justify-center">
                                                            <svg class="w-5 h-5 text-red-600 dark:text-red-400"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div
                                                            class="text-sm font-medium text-red-600 dark:text-red-400">
                                                            Akun ID: {{ $detail->akun_id ?? 'Unknown' }}
                                                        </div>
                                                        <div class="text-xs text-red-500 dark:text-red-400">
                                                            <span
                                                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                                <svg class="w-3 h-3 mr-1" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                                                </svg>
                                                                Data akun hilang
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                                {{ $detail->keterangan ?: '-' }}
                                            </div>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-100">
                                            @if ($detail->debit > 0)
                                                <span class="font-medium text-green-600 dark:text-green-400">
                                                    Rp {{ number_format($detail->debit, 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-100">
                                            @if ($detail->kredit > 0)
                                                <span class="font-medium text-red-600 dark:text-red-400">
                                                    Rp {{ number_format($detail->kredit, 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <td colspan="2"
                                        class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-blue-600 dark:text-blue-400" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                            Total ({{ $jurnalPenutup->details->count() }} entri)
                                        </div>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900 dark:text-gray-100">
                                        <span class="text-green-600 dark:text-green-400">
                                            Rp
                                            {{ number_format($jurnalPenutup->total_debit ?? $jurnalPenutup->details->sum('debit'), 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900 dark:text-gray-100">
                                        <span class="text-red-600 dark:text-red-400">
                                            Rp
                                            {{ number_format($jurnalPenutup->total_kredit ?? $jurnalPenutup->details->sum('kredit'), 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Status Card --}}
                <div
                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm transform transition-all duration-300 hover:shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600 dark:text-purple-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Status
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Status Posting</span>
                                @if ($jurnalPenutup->is_posted)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Posted
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Draft
                                    </span>
                                @endif
                            </div>

                            @if ($jurnalPenutup->is_posted && $jurnalPenutup->posted_at)
                                <div class="flex items-start justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Tanggal Posting</span>
                                    <span class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($jurnalPenutup->posted_at)->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                            @endif

                            @if ($jurnalPenutup->posted_by)
                                <div class="flex items-start justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Diposting oleh</span>
                                    @if ($jurnalPenutup->postedBy)
                                        <span class="text-sm text-gray-900 dark:text-gray-100">
                                            {{ $jurnalPenutup->postedBy->name }}
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            User tidak tersedia
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Actions Card --}}
                @if (!$jurnalPenutup->is_posted)
                    <div
                        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm transform transition-all duration-300 hover:shadow-md">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                Aksi
                            </h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <button type="button"
                                onclick="showPostModal('post', '{{ $jurnalPenutup->no_referensi }}')"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Posting Jurnal
                            </button>

                            <a href="{{ route('keuangan.jurnal-penutup.edit', $jurnalPenutup->no_referensi) }}"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Jurnal
                            </a>

                            <button type="button"
                                onclick="showDeleteModal('{{ $jurnalPenutup->no_referensi }}', '{{ \Carbon\Carbon::parse($jurnalPenutup->tanggal)->format('d F Y') }}')"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus Jurnal
                            </button>
                        </div>
                    </div>
                @endif

                {{-- Audit Trail Card --}}
                <div
                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm transform transition-all duration-300 hover:shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-yellow-600 dark:text-yellow-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Audit Trail
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Dibuat</span>
                                @if ($jurnalPenutup->created_at)
                                    <span class="text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($jurnalPenutup->created_at)->format('d/m/Y H:i') }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4h8a2 2 0 012 2v8a2 2 0 01-2 2H6a2 2 0 01-2-2v-8a2 2 0 012-2z" />
                                        </svg>
                                        Tanggal tidak tersedia
                                    </span>
                                @endif
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Diubah</span>
                                @if ($jurnalPenutup->updated_at)
                                    <span class="text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($jurnalPenutup->updated_at)->format('d/m/Y H:i') }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4h8a2 2 0 012 2v8a2 2 0 01-2 2H6a2 2 0 01-2-2v-8a2 2 0 012-2z" />
                                        </svg>
                                        Tanggal tidak tersedia
                                    </span>
                                @endif
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Dibuat oleh</span>
                                @if ($jurnalPenutup->createdBy)
                                    <span class="text-gray-900 dark:text-gray-100">
                                        {{ $jurnalPenutup->createdBy->name }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        User tidak tersedia
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <div
                    class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mt-2">Konfirmasi Hapus</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400" id="deleteModalMessage">
                        Apakah Anda yakin ingin menghapus jurnal penutup ini?
                    </p>
                    <p class="text-sm text-red-600 dark:text-red-400 mt-2">
                        <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan!
                    </p>
                </div>
                <div class="flex justify-center space-x-3 mt-4">
                    <button id="cancelDelete" type="button"
                        class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 text-sm font-medium rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors duration-200">
                        Batal
                    </button>
                    <button id="confirmDelete" type="button"
                        class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors duration-200">
                        Ya, Hapus Jurnal
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Post Confirmation Modal --}}
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
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                id="postModalTitle">
                                Konfirmasi Posting
                            </h3>
                            <div class="mt-2" id="postModalMessage">
                                <div class="space-y-2">
                                    <p>Apakah Anda yakin ingin memposting jurnal penutup berikut?</p>
                                    <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md text-sm">
                                        <div><strong>No. Referensi:</strong> {{ $jurnalPenutup->no_referensi }}</div>
                                        <div><strong>Tanggal:</strong>
                                            {{ \Carbon\Carbon::parse($jurnalPenutup->tanggal)->format('d F Y') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div id="postModalWarning"
                                class="mt-3 p-3 bg-green-50 dark:bg-green-900/20 rounded-md border border-green-200 dark:border-green-800">
                                <div class="flex">
                                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <div class="ml-3">
                                        <p class="text-sm text-green-700 dark:text-green-300">
                                            <strong>Informasi:</strong> Setelah diposting, jurnal penutup ini akan
                                            mempengaruhi saldo akun dan tidak dapat diedit lagi.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="confirmPostBtn"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 hover:bg-green-700 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        Ya, Posting Jurnal
                    </button>
                    <button type="button" id="cancelPostBtn"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
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

                // Variables
                let currentDeleteForm = null;
                let currentPostForm = null;

                // DOM elements
                const deleteModal = document.getElementById('deleteModal');
                const deleteModalMessage = document.getElementById('deleteModalMessage');
                const cancelDeleteBtn = document.getElementById('cancelDelete');
                const confirmDeleteBtn = document.getElementById('confirmDelete');

                const postModal = document.getElementById('postModal');
                const cancelPostBtn = document.getElementById('cancelPostBtn');
                const confirmPostBtn = document.getElementById('confirmPostBtn');

                // Safe DOM element getter
                function safeGetElement(id) {
                    const element = document.getElementById(id);
                    if (!element) {
                        console.warn(`Element with ID '${id}' not found`);
                    }
                    return element;
                }

                // Show delete modal function
                window.showDeleteModal = function(noReferensi, tanggal) {
                    if (!deleteModal) {
                        console.error('Delete modal not found');
                        return;
                    }

                    // Create delete form
                    currentDeleteForm = document.createElement('form');
                    currentDeleteForm.method = 'POST';
                    currentDeleteForm.action = `{{ url('keuangan/jurnal-penutup') }}/${noReferensi}`;

                    const csrfField = document.createElement('input');
                    csrfField.type = 'hidden';
                    csrfField.name = '_token';
                    csrfField.value = '{{ csrf_token() }}';

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';

                    currentDeleteForm.appendChild(csrfField);
                    currentDeleteForm.appendChild(methodField);

                    // Update modal content
                    if (deleteModalMessage) {
                        deleteModalMessage.innerHTML = `
                            <div class="space-y-2">
                                <p>Apakah Anda yakin ingin menghapus jurnal penutup berikut?</p>
                                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md text-sm">
                                    <div><strong>No. Referensi:</strong> ${noReferensi}</div>
                                    <div><strong>Tanggal:</strong> ${tanggal}</div>
                                </div>
                            </div>
                        `;
                    }

                    // Show modal
                    deleteModal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';

                    if (cancelDeleteBtn) {
                        cancelDeleteBtn.focus();
                    }
                };

                // Show post modal function
                window.showPostModal = function(action, noReferensi) {
                    if (!postModal) {
                        console.error('Post modal not found');
                        return;
                    }

                    // Create post form
                    currentPostForm = document.createElement('form');
                    currentPostForm.method = 'POST';
                    currentPostForm.action = `{{ url('keuangan/jurnal-penutup') }}/${noReferensi}/toggle-post`;

                    const csrfField = document.createElement('input');
                    csrfField.type = 'hidden';
                    csrfField.name = '_token';
                    csrfField.value = '{{ csrf_token() }}';

                    currentPostForm.appendChild(csrfField);

                    // Show modal
                    postModal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';

                    if (confirmPostBtn) {
                        confirmPostBtn.focus();
                    }
                };

                // Close delete modal
                function closeDeleteModal() {
                    if (deleteModal) {
                        deleteModal.classList.add('hidden');
                        document.body.style.overflow = '';
                    }

                    // Clean up form
                    if (currentDeleteForm && currentDeleteForm.parentNode) {
                        currentDeleteForm.parentNode.removeChild(currentDeleteForm);
                    }
                    currentDeleteForm = null;
                }

                // Close post modal
                function closePostModal() {
                    if (postModal) {
                        postModal.classList.add('hidden');
                        document.body.style.overflow = '';
                    }

                    // Clean up form
                    if (currentPostForm && currentPostForm.parentNode) {
                        currentPostForm.parentNode.removeChild(currentPostForm);
                    }
                    currentPostForm = null;

                    // Reset confirm button state
                    if (confirmPostBtn) {
                        confirmPostBtn.disabled = false;
                        confirmPostBtn.textContent = 'Ya, Posting Jurnal';
                    }
                }

                // Event listeners
                if (cancelDeleteBtn) {
                    cancelDeleteBtn.addEventListener('click', closeDeleteModal);
                }

                if (confirmDeleteBtn) {
                    confirmDeleteBtn.addEventListener('click', function() {
                        if (currentDeleteForm) {
                            confirmDeleteBtn.disabled = true;
                            confirmDeleteBtn.textContent = 'Menghapus...';

                            document.body.appendChild(currentDeleteForm);
                            currentDeleteForm.submit();
                        }
                    });
                }

                if (cancelPostBtn) {
                    cancelPostBtn.addEventListener('click', closePostModal);
                }

                if (confirmPostBtn) {
                    confirmPostBtn.addEventListener('click', function() {
                        if (currentPostForm) {
                            confirmPostBtn.disabled = true;
                            confirmPostBtn.textContent = 'Memposting...';

                            document.body.appendChild(currentPostForm);
                            currentPostForm.submit();
                        }
                    });
                }

                // Close modals when clicking outside
                if (deleteModal) {
                    deleteModal.addEventListener('click', function(e) {
                        if (e.target === deleteModal) {
                            closeDeleteModal();
                        }
                    });
                }

                if (postModal) {
                    postModal.addEventListener('click', function(e) {
                        if (e.target === postModal) {
                            closePostModal();
                        }
                    });
                }

                // Handle ESC key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        if (!deleteModal.classList.contains('hidden')) {
                            closeDeleteModal();
                        }
                        if (!postModal.classList.contains('hidden')) {
                            closePostModal();
                        }
                    }
                });

            })(); // End IIFE
        </script>
    @endpush
</x-app-layout>
