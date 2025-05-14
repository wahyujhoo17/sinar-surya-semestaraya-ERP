<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Modern Header with Navigation --}}
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-violet-500 to-indigo-600 h-12 w-1.5 rounded-full mr-4">
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                                Detail Hutang Usaha
                            </h1>
                            <p class="mt-1.5 text-sm text-gray-600 dark:text-gray-400 flex items-center">
                                <span class="font-medium text-indigo-600 dark:text-indigo-400">{{ $po->nomor }}</span>
                                <span class="mx-2 text-gray-400">•</span>
                                <span>{{ $po->supplier->nama }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-2">
                        {{-- Back Button --}}
                        <a href="{{ route('keuangan.hutang-usaha.index') }}"
                            class="group inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-500 transition-all hover:border-gray-300 hover:bg-gray-50 hover:text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-gray-300">
                            <svg class="h-4 w-4 transition-transform group-hover:-translate-x-1"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali
                        </a>

                        {{-- Primary Actions --}}
                        @if ($po->status_pembayaran != 'lunas')
                            <a href="{{ route('keuangan.pembayaran-hutang.create', ['po_id' => $po->id]) }}"
                                class="group inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-600 px-4 py-2 text-sm font-semibold text-white transition-all hover:from-indigo-600 hover:to-violet-700 hover:shadow-lg hover:shadow-indigo-500/30 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:ring-offset-1">
                                <svg class="h-4 w-4 transition-transform group-hover:scale-110"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Bayar
                            </a>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        {{-- Secondary Actions --}}
                        <a href="{{ route('keuangan.hutang-usaha.history', $po->id) }}"
                            class="group inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-br from-sky-500 to-cyan-600 px-4 py-2 text-sm font-semibold text-white transition-all hover:from-sky-600 hover:to-cyan-700 hover:shadow-lg hover:shadow-sky-500/30 focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:ring-offset-1">
                            <svg class="h-4 w-4 transition-transform group-hover:rotate-12"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Riwayat Pembayaran
                        </a>

                        <a href="{{ route('keuangan.hutang-usaha.print', $po->id) }}" target="_blank"
                            class="group inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-br from-fuchsia-500 to-purple-600 px-4 py-2 text-sm font-semibold text-white transition-all hover:from-fuchsia-600 hover:to-purple-700 hover:shadow-lg hover:shadow-purple-500/30 focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:ring-offset-1">
                            <svg class="h-4 w-4 transition-transform group-hover:scale-110"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Print PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            {{-- Status Card --}}
            <div
                class="bg-white dark:bg-gray-800/80 overflow-hidden shadow-md hover:shadow-xl rounded-xl border border-gray-100 dark:border-gray-700/50 transition-all duration-300 relative backdrop-blur-sm">
                <div
                    class="absolute top-0 left-0 w-full h-1 
                    {{ $po->status_pembayaran == 'lunas'
                        ? 'bg-gradient-to-r from-emerald-400 to-green-500'
                        : ($po->status_pembayaran == 'sebagian'
                            ? 'bg-gradient-to-r from-amber-400 to-yellow-500'
                            : 'bg-gradient-to-r from-rose-400 to-red-500') }}">
                </div>
                <div class="p-5">
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 rounded-lg p-3.5
                            {{ $po->status_pembayaran == 'lunas'
                                ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 ring-1 ring-emerald-100/50 dark:ring-emerald-400/20'
                                : ($po->status_pembayaran == 'sebagian'
                                    ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 ring-1 ring-amber-100/50 dark:ring-amber-400/20'
                                    : 'bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 ring-1 ring-rose-100/50 dark:ring-rose-400/20') }}">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                @if ($po->status_pembayaran == 'lunas')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                @endif
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status Pembayaran</p>
                            <div class="mt-1.5 flex items-baseline">
                                <p
                                    class="text-lg font-semibold {{ $po->status_pembayaran == 'lunas'
                                        ? 'text-emerald-600 dark:text-emerald-400'
                                        : ($po->status_pembayaran == 'sebagian'
                                            ? 'text-amber-600 dark:text-amber-400'
                                            : 'text-rose-600 dark:text-rose-400') }}">
                                    @if ($po->status_pembayaran == 'belum_bayar')
                                        Belum Bayar
                                    @elseif($po->status_pembayaran == 'sebagian')
                                        Dibayar Sebagian
                                    @else
                                        Lunas
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Debt Card --}}
            <div
                class="bg-white dark:bg-gray-800/80 overflow-hidden shadow-md hover:shadow-xl rounded-xl border border-gray-100 dark:border-gray-700/50 transition-all duration-300 relative backdrop-blur-sm">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-400 to-violet-500">
                </div>
                <div class="p-5">
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 rounded-lg p-3.5 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 ring-1 ring-indigo-100/50 dark:ring-indigo-400/20">
                            <svg class="h-7 w-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total Hutang</p>
                            <div class="mt-1.5 flex items-baseline">
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Rp {{ number_format($po->total, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Paid Amount Card --}}
            <div
                class="bg-white dark:bg-gray-800/80 overflow-hidden shadow-md hover:shadow-xl rounded-xl border border-gray-100 dark:border-gray-700/50 transition-all duration-300 relative backdrop-blur-sm">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-teal-500">
                </div>
                <div class="p-5">
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 rounded-lg p-3.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 ring-1 ring-emerald-100/50 dark:ring-emerald-400/20">
                            <svg class="h-7 w-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Sudah Dibayar</p>
                            <div class="mt-1.5 flex items-baseline">
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Rp {{ number_format($po->total - $sisaHutang, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Remaining Amount Card --}}
            <div
                class="bg-white dark:bg-gray-800/80 overflow-hidden shadow-md hover:shadow-xl rounded-xl border border-gray-100 dark:border-gray-700/50 transition-all duration-300 relative backdrop-blur-sm">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-fuchsia-400 to-purple-500">
                </div>
                <div class="p-5">
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 rounded-lg p-3.5 bg-fuchsia-100 dark:bg-fuchsia-900/30 text-fuchsia-600 dark:text-fuchsia-400 ring-1 ring-fuchsia-100/50 dark:ring-fuchsia-400/20">
                            <svg class="h-7 w-7" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Sisa Hutang</p>
                            <div class="mt-1.5 flex items-baseline">
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Rp {{ number_format($sisaHutang, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Sections --}}
        <div class="grid grid-cols-1 gap-8 mb-8">
            {{-- Purchase Order Information - Modernized Layout --}}
            <div
                class="bg-white dark:bg-gray-800/80 overflow-hidden shadow-md rounded-xl border border-gray-100 dark:border-gray-700/50 backdrop-blur-sm">
                <div
                    class="px-6 py-4 bg-gradient-to-br from-indigo-50 to-indigo-100/80 dark:from-gray-800 dark:to-gray-750 border-b border-gray-200/60 dark:border-gray-700/60">
                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Informasi Purchase Order
                        </h3>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Left column - Order Details --}}
                        <div
                            class="bg-indigo-50/50 dark:bg-indigo-900/10 rounded-xl p-5 border border-indigo-100/60 dark:border-indigo-800/20">
                            <h4
                                class="text-sm uppercase font-semibold text-indigo-700 dark:text-indigo-300 mb-4 flex items-center gap-2">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                Detail Pesanan
                            </h4>

                            <div class="space-y-4">
                                <div class="flex flex-col">
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Nomor PO</span>
                                    <span
                                        class="mt-1 text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                        {{ $po->nomor }}
                                        <span
                                            class="px-2 py-0.5 text-xs font-medium rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300">
                                            #PO
                                        </span>
                                    </span>
                                </div>

                                <div class="flex flex-col">
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Tanggal
                                        PO</span>
                                    <span class="mt-1 text-base text-gray-900 dark:text-white">
                                        {{ $po->tanggal_po ? $po->tanggal_po->format('d F Y') : '-' }}
                                    </span>
                                </div>

                                <div class="flex flex-col">
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Jatuh
                                        Tempo</span>
                                    <div class="mt-1 flex items-center gap-2">
                                        <span class="text-base text-gray-900 dark:text-white">
                                            {{ $po->tanggal_jatuh_tempo ? $po->tanggal_jatuh_tempo->format('d F Y') : '-' }}
                                        </span>
                                        @if ($po->status_pembayaran != 'lunas' && $po->tanggal_jatuh_tempo)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ now()->gt($po->tanggal_jatuh_tempo)
                                                    ? 'bg-rose-100 dark:bg-rose-900/30 text-rose-800 dark:text-rose-300 ring-1 ring-rose-100/60 dark:ring-rose-500/20'
                                                    : 'bg-sky-100 dark:bg-sky-900/30 text-sky-800 dark:text-sky-300 ring-1 ring-sky-100/60 dark:ring-sky-500/20' }}">
                                                {{ now()->gt($po->tanggal_jatuh_tempo)
                                                    ? 'Terlambat ' . now()->diffInDays($po->tanggal_jatuh_tempo) . ' hari'
                                                    : 'Dalam ' . now()->diffInDays($po->tanggal_jatuh_tempo) . ' hari' }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-col pt-1">
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Status PO</span>
                                    <span class="mt-1">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                            {{ $po->status == 'selesai'
                                                ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 ring-1 ring-emerald-100/60 dark:ring-emerald-500/20'
                                                : 'bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300 ring-1 ring-amber-100/60 dark:ring-amber-500/20' }}">
                                            <svg class="h-3 w-3 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                @if ($po->status == 'selesai')
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                @endif
                                            </svg>
                                            {{ $po->status == 'selesai' ? 'Selesai' : 'Proses' }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Right column - Supplier Details --}}
                        <div
                            class="bg-violet-50/50 dark:bg-violet-900/10 rounded-xl p-5 border border-violet-100/60 dark:border-violet-800/20">
                            <h4
                                class="text-sm uppercase font-semibold text-violet-700 dark:text-violet-300 mb-4 flex items-center gap-2">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Informasi Supplier
                            </h4>

                            <div class="space-y-4">
                                <div class="flex flex-col">
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Nama
                                        Supplier</span>
                                    <span class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $po->supplier->nama }}
                                    </span>
                                </div>

                                <div class="flex flex-col">
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Telepon</span>
                                    <span class="mt-1 text-base text-gray-900 dark:text-white flex items-center gap-2">
                                        @if ($po->supplier->telepon)
                                            <svg class="h-4 w-4 text-gray-400 dark:text-gray-500"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                        @endif
                                        {{ $po->supplier->telepon ?? '-' }}
                                    </span>
                                </div>

                                <div class="flex flex-col">
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Alamat</span>
                                    <span class="mt-1 text-base text-gray-900 dark:text-white">
                                        {{ $po->supplier->alamat ?? '-' }}
                                    </span>
                                </div>

                                <div class="flex flex-col pt-1">
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Dibuat
                                        Oleh</span>
                                    <span class="mt-1 flex items-center gap-2">
                                        <span
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-700">
                                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                                {{ $po->user ? substr($po->user->name, 0, 2) : 'U' }}
                                            </span>
                                        </span>
                                        <span class="text-base text-gray-900 dark:text-white">
                                            {{ $po->user->name ?? '-' }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Additional Information / Notes --}}
                    @if ($po->keterangan)
                        <div
                            class="mt-6 bg-gray-50 dark:bg-gray-900/20 rounded-xl p-5 border border-gray-200/60 dark:border-gray-700/40">
                            <h4
                                class="text-sm uppercase font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                                Keterangan
                            </h4>

                            <div class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                {{ $po->keterangan }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Products Table --}}
            <div
                class="bg-white dark:bg-gray-800/80 overflow-hidden shadow-md rounded-xl border border-gray-100 dark:border-gray-700/50 backdrop-blur-sm">
                <div
                    class="px-6 py-4 bg-gradient-to-br from-violet-50 to-violet-100/80 dark:from-gray-800 dark:to-gray-750 border-b border-gray-200/60 dark:border-gray-700/60">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Detail Produk
                        </h3>
                        @if ($po->status_pembayaran != 'lunas')
                            <div
                                class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300 ring-1 ring-indigo-100/60 dark:ring-indigo-500/20">
                                Sisa: Rp {{ number_format($po->total - $po->total_dibayar, 0, ',', '.') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/30">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Nama Produk
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Jumlah
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Harga
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Diskon
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Subtotal
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @if ($po->details && count($po->details) > 0)
                                @foreach ($po->details as $detail)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors duration-200">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-10 w-10 bg-violet-100 dark:bg-violet-900/20 rounded-md flex items-center justify-center ring-1 ring-violet-100/60 dark:ring-violet-500/20">
                                                    <svg class="h-5 w-5 text-violet-500 dark:text-violet-400"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                    </svg>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $detail->produk ? $detail->produk->nama : $detail->keterangan }}
                                                    </div>
                                                    @if ($detail->produk && $detail->produk->kode)
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $detail->produk->kode }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span
                                                    class="inline-flex items-center justify-center px-2.5 py-1 rounded-md bg-violet-50 dark:bg-violet-900/20 text-violet-700 dark:text-violet-400 text-sm font-medium ring-1 ring-violet-100/60 dark:ring-violet-500/20">
                                                    {{ $detail->quantity }}
                                                </span>
                                                <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                                    @php
                                                        // Handle the unit (satuan) from either a direct property or a relationship
                                                        $satuanName = '';
                                                        if (isset($detail->satuan) && is_object($detail->satuan)) {
                                                            $satuanName = $detail->satuan->nama;
                                                        } elseif (
                                                            isset($detail->satuan) &&
                                                            is_string($detail->satuan)
                                                        ) {
                                                            $satuanName = $detail->satuan;
                                                        } elseif (
                                                            isset($detail->satuan_id) &&
                                                            $detail->produk &&
                                                            $detail->produk->satuan
                                                        ) {
                                                            $satuanName = $detail->produk->satuan->nama;
                                                        }
                                                    @endphp
                                                    {{ $satuanName }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">Rp
                                                {{ number_format($detail->harga, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($detail->diskon && $detail->diskon > 0)
                                                <span
                                                    class="inline-flex items-center justify-center px-2 py-1 text-xs font-medium rounded-full bg-teal-100 dark:bg-teal-900/20 text-teal-800 dark:text-teal-400 ring-1 ring-teal-100/60 dark:ring-teal-500/20">
                                                    {{ $detail->diskon }}%
                                                </span>
                                            @else
                                                <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">Rp
                                                {{ number_format($detail->subtotal, 0, ',', '.') }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center justify-center py-5">
                                            <svg class="h-10 w-10 text-gray-400 dark:text-gray-600 mb-2"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                            </svg>
                                            <p class="text-sm">Tidak ada data produk</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-900/30">
                            <tr>
                                <th colspan="4"
                                    class="px-6 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">
                                    Subtotal:
                                </th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                    Rp {{ number_format($po->subtotal, 0, ',', '.') }}
                                </th>
                            </tr>
                            <tr>
                                <th colspan="4"
                                    class="px-6 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">
                                    Diskon:
                                </th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $po->diskon ? $po->diskon . '%' : '-' }}
                                </th>
                            </tr>
                            <tr>
                                <th colspan="4"
                                    class="px-6 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">
                                    PPN:
                                </th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $po->ppn ? $po->ppn . '%' : '-' }}
                                </th>
                            </tr>
                            <tr>
                                <th colspan="4"
                                    class="px-6 py-3 text-right text-sm font-bold text-gray-900 dark:text-white">
                                    Total:
                                </th>
                                <th class="px-6 py-3 text-left text-sm font-bold text-gray-900 dark:text-white">
                                    Rp {{ number_format($po->total, 0, ',', '.') }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Payment History --}}
            @if ($po->payments && count($po->payments) > 0)
                <div
                    class="bg-white dark:bg-gray-800/80 overflow-hidden shadow-md rounded-xl border border-gray-100 dark:border-gray-700/50 backdrop-blur-sm">
                    <div
                        class="px-6 py-4 bg-gradient-to-br from-sky-50 to-sky-100/80 dark:from-gray-800 dark:to-gray-750 border-b border-gray-200/60 dark:border-gray-700/60">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Riwayat Pembayaran
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/30">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Jumlah
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Metode
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Keterangan
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($po->payments as $payment)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $payment->tanggal_pembayaran ? $payment->tanggal_pembayaran->format('d/m/Y') : '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">Rp
                                                {{ number_format($payment->jumlah, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $payment->metode_pembayaran == 'tunai'
                                                    ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 ring-1 ring-emerald-100/60 dark:ring-emerald-500/20'
                                                    : 'bg-sky-100 dark:bg-sky-900/30 text-sky-800 dark:text-sky-300 ring-1 ring-sky-100/60 dark:ring-sky-500/20' }}">
                                                {{ $payment->metode_pembayaran == 'tunai' ? 'Tunai' : 'Transfer' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $payment->keterangan ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('keuangan.pembayaran-hutang.show', $payment->id) }}"
                                                class="text-violet-600 dark:text-violet-400 hover:text-violet-900 dark:hover:text-violet-300 font-medium hover:underline">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-900/30">
                                <tr>
                                    <th colspan="1"
                                        class="px-6 py-3 text-right text-sm font-bold text-gray-900 dark:text-white">
                                        Total Pembayaran:
                                    </th>
                                    <th class="px-6 py-3 text-left text-sm font-bold text-gray-900 dark:text-white">
                                        Rp {{ number_format($po->total_dibayar, 0, ',', '.') }}
                                    </th>
                                    <th colspan="3"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Returns Section --}}
            @if (isset($returns) && count($returns) > 0)
                <div
                    class="bg-white dark:bg-gray-800/80 overflow-hidden shadow-md rounded-xl border border-gray-100 dark:border-gray-700/50 backdrop-blur-sm">
                    <div
                        class="px-6 py-4 bg-gradient-to-br from-rose-50 to-rose-100/80 dark:from-gray-800 dark:to-gray-750 border-b border-gray-200/60 dark:border-gray-700/60">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Retur Pembelian
                            </h3>
                            <div
                                class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium bg-rose-100 dark:bg-rose-900/30 text-rose-800 dark:text-rose-300 ring-1 ring-rose-100/60 dark:ring-rose-500/20">
                                Total: Rp {{ number_format($totalReturValue, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/30">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        No. Retur
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Nilai
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Keterangan
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($returns as $return)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $return->nomor }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $return->tanggal ? $return->tanggal->format('d/m/Y') : '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $returValue = 0;
                                                foreach ($return->details as $detail) {
                                                    $returValue += $detail->harga * $detail->qty;
                                                }
                                            @endphp
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                Rp {{ number_format($returValue, 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $return->keterangan ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('pembelian.retur.show', $return->id) }}"
                                                class="text-rose-600 dark:text-rose-400 hover:text-rose-900 dark:hover:text-rose-300 font-medium hover:underline">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Financial Summary Section --}}
            <div
                class="bg-white dark:bg-gray-800/80 overflow-hidden shadow-md rounded-xl border border-gray-100 dark:border-gray-700/50 backdrop-blur-sm">
                <div
                    class="px-6 py-4 bg-gradient-to-br from-gray-50 to-gray-100/80 dark:from-gray-800 dark:to-gray-750 border-b border-gray-200/60 dark:border-gray-700/60">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Ringkasan Keuangan
                    </h3>
                </div>
                <div class="p-6">
                    <div class="overflow-hidden bg-gray-50 dark:bg-gray-900/30 sm:rounded-lg shadow-inner">
                        <div class="px-6 py-5 sm:px-6">
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Hutang</dt>
                                    <dd class="mt-2 text-base font-semibold text-gray-900 dark:text-white">
                                        Rp {{ number_format($po->total, 0, ',', '.') }}
                                    </dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pembayaran
                                    </dt>
                                    <dd class="mt-2 text-base font-semibold text-emerald-600 dark:text-emerald-400">
                                        Rp {{ number_format($po->total - $sisaHutang, 0, ',', '.') }}
                                    </dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Retur</dt>
                                    <dd class="mt-2 text-base font-semibold text-rose-600 dark:text-rose-400">
                                        Rp {{ number_format($totalReturValue, 0, ',', '.') }}
                                    </dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Sisa Hutang</dt>
                                    <dd class="mt-2 text-base font-bold text-indigo-600 dark:text-indigo-400">
                                        Rp {{ number_format($sisaHutang, 0, ',', '.') }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Add any JavaScript enhancements here

                // Initialize tooltips
                const tooltipTriggerList = document.querySelectorAll('[data-tooltip-target]');
                tooltipTriggerList.forEach(tooltipTriggerEl => {
                    // Your tooltip initialization code here if needed
                });

                // Add smooth transitions for hover states
                const hoverElements = document.querySelectorAll('.hover\\:bg-gray-50');
                hoverElements.forEach(el => {
                    el.addEventListener('mouseenter', () => {
                        el.style.transition = 'background-color 0.2s ease-in-out';
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
