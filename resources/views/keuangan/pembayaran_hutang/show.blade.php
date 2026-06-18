<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-indigo-500 to-violet-600 h-12 w-1.5 rounded-full mr-4">
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                                Detail Pembayaran Hutang
                            </h1>
                            <p class="mt-1.5 text-sm text-gray-600 dark:text-gray-400">
                                <span
                                    class="font-medium text-indigo-600 dark:text-indigo-400">{{ $payment->nomor }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('keuangan.hutang-usaha.index') }}"
                        class="group inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-500 transition-all hover:border-gray-300 hover:bg-gray-50 hover:text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-gray-300">
                        <svg class="h-4 w-4 transition-transform group-hover:-translate-x-1"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>

                    @if (auth()->user()->hasRole('superadmin') ||
                            auth()->user()->hasRole('direktur_utama') ||
                            auth()->user()->hasRole('administrator') ||
                            auth()->user()->hasRole('admin'))
                        <a href="{{ route('keuangan.pembayaran-hutang.edit', $payment->id) }}"
                            class="group inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-br from-amber-500 to-orange-600 px-4 py-2 text-sm font-semibold text-white transition-all hover:from-amber-600 hover:to-orange-700 hover:shadow-lg hover:shadow-amber-500/30 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:ring-offset-1">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>
                        <form action="{{ route('keuangan.pembayaran-hutang.destroy', $payment->id) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus pembayaran ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="group inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-br from-rose-500 to-red-600 px-4 py-2 text-sm font-semibold text-white transition-all hover:from-rose-600 hover:to-red-700 hover:shadow-lg hover:shadow-rose-500/30 focus:outline-none focus:ring-2 focus:ring-rose-500/50 focus:ring-offset-1">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 lg:gap-8 mb-8">
            {{-- Left Column - Payment Details --}}
            <div class="xl:col-span-2 space-y-6">
                {{-- Payment Information Card --}}
                <div
                    class="bg-white dark:bg-gray-800/80 overflow-hidden shadow-md rounded-xl border border-gray-100 dark:border-gray-700/50 backdrop-blur-sm">
                    <div
                        class="px-6 py-4 bg-gradient-to-br from-indigo-50 to-violet-50 dark:from-gray-800 dark:to-gray-750 border-b border-gray-200/60 dark:border-gray-700/60">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Informasi Pembayaran
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nomor
                                        Pembayaran</dt>
                                    <dd class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $payment->nomor }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal
                                        Pembayaran</dt>
                                    <dd class="text-base text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($payment->tanggal)->translatedFormat('d F Y') }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Metode
                                        Pembayaran</dt>
                                    <dd>
                                        @if ($payment->metode_pembayaran == 'tunai')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 ring-1 ring-emerald-100/60 dark:ring-emerald-500/20">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                Tunai
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-sky-100 dark:bg-sky-900/30 text-sky-800 dark:text-sky-300 ring-1 ring-sky-100/60 dark:ring-sky-500/20">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                </svg>
                                                Transfer
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Jumlah
                                        Pembayaran</dt>
                                    <dd class="text-xl font-bold text-indigo-600 dark:text-indigo-400">
                                        Rp {{ number_format($payment->jumlah, 0, ',', '.') }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">No. Referensi
                                    </dt>
                                    <dd class="text-base text-gray-900 dark:text-white">
                                        {{ $payment->no_referensi ?? '-' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        @if ($payment->metode_pembayaran == 'tunai')
                                            Kas
                                        @else
                                            Rekening Bank
                                        @endif
                                    </dt>
                                    <dd class="text-base text-gray-900 dark:text-white">
                                        @if ($payment->metode_pembayaran == 'tunai' && $payment->kas)
                                            {{ $payment->kas->nama }}
                                        @elseif ($payment->metode_pembayaran != 'tunai' && $payment->rekeningBank)
                                            {{ $payment->rekeningBank->nama_bank }} -
                                            {{ $payment->rekeningBank->nomor_rekening }}
                                        @else
                                            -
                                        @endif
                                    </dd>
                                </div>
                            </div>
                        </div>

                        @if ($payment->catatan)
                            <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Catatan</dt>
                                <dd
                                    class="text-base text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3">
                                    {{ $payment->catatan }}
                                </dd>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Purchase Order Information Card --}}
                @if ($payment->purchaseOrder)
                    <div
                        class="bg-white dark:bg-gray-800/80 overflow-hidden shadow-md rounded-xl border border-gray-100 dark:border-gray-700/50 backdrop-blur-sm">
                        <div
                            class="px-6 py-4 bg-gradient-to-br from-violet-50 to-purple-50 dark:from-gray-800 dark:to-gray-750 border-b border-gray-200/60 dark:border-gray-700/60">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="h-5 w-5 text-violet-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Informasi Purchase Order
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nomor PO
                                        </dt>
                                        <dd class="text-base font-semibold text-gray-900 dark:text-white">
                                            <a href="{{ route('keuangan.hutang-usaha.show', $payment->purchaseOrder->id) }}"
                                                class="text-violet-600 hover:text-violet-700 dark:text-violet-400 dark:hover:text-violet-300 font-medium hover:underline">
                                                {{ $payment->purchaseOrder->nomor }}
                                                <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                            </a>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal
                                            PO</dt>
                                        <dd class="text-base text-gray-900 dark:text-white">
                                            {{ $payment->purchaseOrder->tanggal_po ? $payment->purchaseOrder->tanggal_po->format('d F Y') : '-' }}
                                        </dd>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total PO
                                        </dt>
                                        <dd class="text-lg font-semibold text-gray-900 dark:text-white">
                                            Rp {{ number_format($payment->purchaseOrder->total, 0, ',', '.') }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Status
                                            Pembayaran</dt>
                                        <dd>
                                            @php $po = $payment->purchaseOrder; @endphp
                                            @if ($po->status_pembayaran == 'lunas')
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 ring-1 ring-emerald-100/60 dark:ring-emerald-500/20">Lunas</span>
                                            @elseif($po->status_pembayaran == 'sebagian')
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300 ring-1 ring-amber-100/60 dark:ring-amber-500/20">Dibayar
                                                    Sebagian</span>
                                            @elseif($po->status_pembayaran == 'kelebihan_bayar')
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 ring-1 ring-blue-100/60 dark:ring-blue-500/20">Kelebihan
                                                    Bayar</span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-rose-100 dark:bg-rose-900/30 text-rose-800 dark:text-rose-300 ring-1 ring-rose-100/60 dark:ring-rose-500/20">Belum
                                                    Bayar</span>
                                            @endif
                                        </dd>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Supplier Information Card --}}
                @if ($payment->supplier)
                    <div
                        class="bg-white dark:bg-gray-800/80 overflow-hidden shadow-md rounded-xl border border-gray-100 dark:border-gray-700/50 backdrop-blur-sm">
                        <div
                            class="px-6 py-4 bg-gradient-to-br from-sky-50 to-cyan-50 dark:from-gray-800 dark:to-gray-750 border-b border-gray-200/60 dark:border-gray-700/60">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="h-5 w-5 text-sky-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Informasi Supplier
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nama
                                            Supplier</dt>
                                        <dd class="text-base font-semibold text-gray-900 dark:text-white">
                                            {{ $payment->supplier->nama }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Telepon
                                        </dt>
                                        <dd class="text-base text-gray-900 dark:text-white">
                                            {{ $payment->supplier->telepon ?? '-' }}
                                        </dd>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Alamat
                                        </dt>
                                        <dd class="text-base text-gray-900 dark:text-white">
                                            {{ $payment->supplier->alamat ?? '-' }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Dibuat
                                            Oleh</dt>
                                        <dd class="text-base text-gray-900 dark:text-white">
                                            {{ $payment->user->name ?? '-' }}
                                        </dd>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Right Column - Summary --}}
            <div class="space-y-6">
                <div
                    class="bg-white dark:bg-gray-800/80 overflow-hidden shadow-md rounded-xl border border-gray-100 dark:border-gray-700/50 backdrop-blur-sm">
                    <div
                        class="px-6 py-4 bg-gradient-to-br from-gray-50 to-gray-100/80 dark:from-gray-800 dark:to-gray-750 border-b border-gray-200/60 dark:border-gray-700/60">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Ringkasan
                        </h3>
                    </div>
                    <div class="p-6">
                        <dl class="space-y-4">
                            <div
                                class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-700">
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Jumlah Dibayar</dt>
                                <dd class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                                    Rp {{ number_format($payment->jumlah, 0, ',', '.') }}
                                </dd>
                            </div>
                            <div
                                class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-700">
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Metode</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $payment->metode_pembayaran == 'tunai' ? 'Tunai' : 'Transfer' }}
                                </dd>
                            </div>
                            <div
                                class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-700">
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Tanggal</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($payment->tanggal)->format('d/m/Y') }}
                                </dd>
                            </div>
                            @if ($payment->no_referensi)
                                <div class="flex justify-between items-center">
                                    <dt class="text-sm text-gray-500 dark:text-gray-400">No. Referensi</dt>
                                    <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $payment->no_referensi }}
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        {{-- Riwayat Aktivitas / Log --}}
        @if ($logs->isNotEmpty())
            <div class="mt-8">
                <div
                    class="bg-white dark:bg-gray-800/80 overflow-hidden shadow-md rounded-xl border border-gray-100 dark:border-gray-700/50 backdrop-blur-sm">
                    <div
                        class="px-6 py-4 bg-gradient-to-br from-slate-50 to-gray-100/80 dark:from-gray-800 dark:to-gray-750 border-b border-gray-200/60 dark:border-gray-700/60">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="h-5 w-5 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Riwayat Aktivitas
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach ($logs as $log)
                                <div
                                    class="flex items-start gap-4 pb-4 border-b border-gray-100 dark:border-gray-700 last:border-0 last:pb-0">
                                    <div class="flex-shrink-0">
                                        @if ($log->aktivitas === 'ubah')
                                            <div
                                                class="h-9 w-9 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                                                <svg class="h-4 w-4 text-amber-600 dark:text-amber-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </div>
                                        @elseif ($log->aktivitas === 'hapus')
                                            <div
                                                class="h-9 w-9 rounded-full bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center">
                                                <svg class="h-4 w-4 text-rose-600 dark:text-rose-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </div>
                                        @else
                                            <div
                                                class="h-9 w-9 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                                <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $log->user->name ?? 'System' }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $log->created_at->translatedFormat('d M Y H:i') }}
                                            </span>
                                        </div>
                                        <div class="mt-1">
                                            @if ($log->aktivitas === 'ubah')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300">
                                                    Mengedit pembayaran
                                                </span>
                                                @if ($log->detail)
                                                    @php $detail = is_string($log->detail) ? json_decode($log->detail, true) : $log->detail; @endphp
                                                    @if (isset($detail['perubahan']) && count($detail['perubahan']) > 0)
                                                        <div
                                                            class="mt-2 text-xs text-gray-600 dark:text-gray-400 space-y-1">
                                                            @foreach ($detail['perubahan'] as $field => $change)
                                                                <div class="flex items-center gap-1">
                                                                    <span
                                                                        class="font-medium">{{ $field }}:</span>
                                                                    <span
                                                                        class="text-rose-500 line-through">{{ is_scalar($change['lama'] ?? '') ? $change['lama'] : '-' }}</span>
                                                                    <svg class="h-3 w-3 text-gray-400" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M9 5l7 7-7 7" />
                                                                    </svg>
                                                                    <span
                                                                        class="text-emerald-600 font-medium">{{ is_scalar($change['baru'] ?? '') ? $change['baru'] : '-' }}</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                @endif
                                            @elseif ($log->aktivitas === 'hapus')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-rose-100 dark:bg-rose-900/30 text-rose-800 dark:text-rose-300">
                                                    Menghapus pembayaran
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                                    {{ ucfirst($log->aktivitas) }}
                                                </span>
                                            @endif
                                        </div>
                                        @if ($log->ip_address)
                                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                                IP: {{ $log->ip_address }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
