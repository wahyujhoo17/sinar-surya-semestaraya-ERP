<x-app-layout :breadcrumbs="[
    ['name' => 'Keuangan', 'url' => '#'],
    ['name' => 'Pembayaran Piutang', 'url' => route('keuangan.pembayaran-piutang.index')],
    ['name' => 'Detail Pembayaran', 'url' => '#'],
]" :currentPage="'Detail Pembayaran Piutang - ' . $pembayaran->nomor">

    <div class="w-full max-w-none py-6 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-none mx-auto">
            {{-- Header Section --}}
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center space-x-4">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-green-500 to-emerald-600 h-12 w-1.5 rounded-full mr-4">
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Detail Pembayaran Piutang</h1>
                            <p class="mt-1 text-lg text-gray-600 dark:text-gray-400">
                                Nomor: <span
                                    class="font-semibold text-green-600 dark:text-green-400">{{ $pembayaran->nomor }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">

                        <a href="{{ route('keuangan.piutang-usaha.index') }}"
                            class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>


            {{-- Main Content Grid --}}
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 lg:gap-8">
                {{-- Left Column - Payment Details --}}
                <div class="xl:col-span-2 space-y-6">
                    {{-- Payment Information Card --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div
                            class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
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
                                            {{ $pembayaran->nomor }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal
                                            Pembayaran</dt>
                                        <dd class="text-base text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($pembayaran->tanggal)->translatedFormat('d F Y') }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Metode
                                            Pembayaran</dt>
                                        <dd class="text-base text-gray-900 dark:text-white">
                                            @php
                                                $badgeClasses = match ($pembayaran->metode_pembayaran) {
                                                    'bank'
                                                        => 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400',
                                                    'kas'
                                                        => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
                                                    default
                                                        => 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400',
                                                };
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $badgeClasses }}">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    @if ($pembayaran->metode_pembayaran === 'bank')
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                                        </path>
                                                    @elseif($pembayaran->metode_pembayaran === 'kas')
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                                        </path>
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    @endif
                                                </svg>
                                                {{ ucfirst($pembayaran->metode_pembayaran) }}
                                            </span>
                                        </dd>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Jumlah
                                            Pembayaran</dt>
                                        <dd class="text-xl font-bold text-green-600 dark:text-green-400">
                                            Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">No.
                                            Referensi</dt>
                                        <dd class="text-base text-gray-900 dark:text-white">
                                            {{ $pembayaran->no_referensi ?? '-' }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                            @if ($pembayaran->metode_pembayaran === 'bank')
                                                Rekening Bank
                                            @elseif($pembayaran->metode_pembayaran === 'kas')
                                                Kas
                                            @else
                                                Akun
                                            @endif
                                        </dt>
                                        <dd class="text-base text-gray-900 dark:text-white">
                                            @if ($pembayaran->metode_pembayaran === 'bank' && $pembayaran->rekeningBank)
                                                {{ $pembayaran->rekeningBank->nama_bank }} -
                                                {{ $pembayaran->rekeningBank->nomor_rekening }}
                                            @elseif($pembayaran->metode_pembayaran === 'kas' && $pembayaran->kas)
                                                {{ $pembayaran->kas->nama }}
                                            @else
                                                -
                                            @endif
                                        </dd>
                                    </div>
                                </div>
                            </div>

                            @if ($pembayaran->catatan)
                                <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Catatan</dt>
                                    <dd
                                        class="text-base text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3">
                                        {{ $pembayaran->catatan }}
                                    </dd>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Invoice Information Card --}}
                    @if ($pembayaran->invoice)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <div
                                class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Informasi Invoice
                                </h3>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nomor
                                                Invoice</dt>
                                            <dd class="text-base font-semibold text-gray-900 dark:text-white">
                                                @if ($pembayaran->invoice->nomor)
                                                    <a href="{{ route('penjualan.invoice.show', $pembayaran->invoice->id) }}"
                                                        target="_blank"
                                                        class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium hover:underline">
                                                        {{ $pembayaran->invoice->nomor }}
                                                        <svg class="w-4 h-4 inline ml-1" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                                Tanggal Invoice</dt>
                                            <dd class="text-base text-gray-900 dark:text-white">
                                                {{ \Carbon\Carbon::parse($pembayaran->invoice->tanggal)->translatedFormat('d F Y') }}
                                            </dd>
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total
                                                Invoice</dt>
                                            <dd class="text-lg font-semibold text-gray-900 dark:text-white">
                                                Rp {{ number_format($pembayaran->invoice->total, 0, ',', '.') }}
                                            </dd>
                                        </div>
                                        @if ($pembayaran->invoice->jatuh_tempo)
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                                    Jatuh Tempo</dt>
                                                <dd class="text-base text-gray-900 dark:text-white">
                                                    {{ \Carbon\Carbon::parse($pembayaran->invoice->jatuh_tempo)->translatedFormat('d F Y') }}
                                                </dd>
                                            </div>
                                        @endif
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Sisa
                                                Piutang</dt>
                                            <dd class="text-lg font-semibold text-red-600 dark:text-red-400">
                                                Rp {{ number_format($pembayaran->invoice->sisa_piutang, 0, ',', '.') }}
                                            </dd>
                                        </div>
                                    </div>
                                </div>

                                {{-- Breakdown pembayaran dan potongan --}}
                                @if ($pembayaran->invoice->uang_muka_terapkan > 0 || $pembayaran->invoice->kredit_terapkan > 0)
                                    <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700">
                                        <h4
                                            class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-500"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Rincian Potongan & Uang Muka
                                        </h4>
                                        <div class="space-y-3">
                                            @if ($pembayaran->invoice->uang_muka_terapkan > 0)
                                                <div
                                                    class="flex justify-between items-center py-2 px-3 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                                                    <span class="text-sm text-blue-700 dark:text-blue-300">Uang Muka
                                                        Diterapkan:</span>
                                                    <span class="font-semibold text-blue-800 dark:text-blue-200">Rp
                                                        {{ number_format($pembayaran->invoice->uang_muka_terapkan, 0, ',', '.') }}</span>
                                                </div>
                                            @endif
                                            @if ($pembayaran->invoice->kredit_terapkan > 0)
                                                <div
                                                    class="flex justify-between items-center py-2 px-3 bg-green-50 dark:bg-green-900/30 rounded-lg">
                                                    <span class="text-sm text-green-700 dark:text-green-300">Nota
                                                        Kredit Diterapkan:</span>
                                                    <span class="font-semibold text-green-800 dark:text-green-200">Rp
                                                        {{ number_format($pembayaran->invoice->kredit_terapkan, 0, ',', '.') }}</span>
                                                </div>
                                            @endif
                                            @php
                                                $totalPembayaranLain = $pembayaran->invoice
                                                    ->pembayaranPiutang()
                                                    ->where('id', '!=', $pembayaran->id)
                                                    ->sum('jumlah');
                                            @endphp
                                            @if ($totalPembayaranLain > 0)
                                                <div
                                                    class="flex justify-between items-center py-2 px-3 bg-purple-50 dark:bg-purple-900/30 rounded-lg">
                                                    <span
                                                        class="text-sm text-purple-700 dark:text-purple-300">Pembayaran
                                                        Lainnya:</span>
                                                    <span class="font-semibold text-purple-800 dark:text-purple-200">Rp
                                                        {{ number_format($totalPembayaranLain, 0, ',', '.') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Customer Information Card --}}
                    @if ($pembayaran->customer)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <div
                                class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-violet-50 dark:from-purple-900/20 dark:to-violet-900/20">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    Informasi Customer
                                </h3>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Kode
                                                Customer</dt>
                                            <dd class="text-base font-semibold text-gray-900 dark:text-white">
                                                {{ $pembayaran->customer->kode }}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nama
                                            </dt>
                                            <dd class="text-base text-gray-900 dark:text-white">
                                                {{ $pembayaran->customer->nama }}
                                            </dd>
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        @if ($pembayaran->customer->company)
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                                    Perusahaan</dt>
                                                <dd class="text-base text-gray-900 dark:text-white">
                                                    {{ $pembayaran->customer->company }}
                                                </dd>
                                            </div>
                                        @endif
                                        @if ($pembayaran->customer->phone)
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                                    Telepon</dt>
                                                <dd class="text-base text-gray-900 dark:text-white">
                                                    {{ $pembayaran->customer->phone }}
                                                </dd>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Right Column - Summary & Actions --}}
                <div class="xl:col-span-1 space-y-6">
                    {{-- Payment Status Card --}}
                    <div
                        class="bg-gradient-to-br from-green-50 to-emerald-100 dark:from-green-900/20 dark:to-emerald-900/30 rounded-2xl shadow-lg border border-green-200 dark:border-green-700 overflow-hidden">
                        <div class="p-6">
                            <div class="text-center">
                                <div
                                    class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 dark:bg-green-900/50 mb-4">
                                    <svg class="h-8 w-8 text-green-600 dark:text-green-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-green-800 dark:text-green-200 mb-2">Pembayaran
                                    Berhasil</h3>
                                <p class="text-sm text-green-600 dark:text-green-300">
                                    Pembayaran telah dicatat pada
                                    {{ \Carbon\Carbon::parse($pembayaran->created_at)->translatedFormat('d F Y \p\u\k\u\l H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Actions Card --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z">
                                    </path>
                                </svg>
                                Aksi Cepat
                            </h3>
                        </div>
                        <div class="p-6 space-y-3">

                            @if ($pembayaran->invoice)
                                <a href="{{ route('penjualan.invoice.show', $pembayaran->invoice->id) }}"
                                    target="_blank"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Lihat Invoice
                                </a>
                            @endif

                        </div>
                    </div>

                    {{-- User Info Card --}}
                    @if ($pembayaran->user)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    Informasi Pencatat
                                </h3>
                            </div>
                            <div class="p-6">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                {{ substr($pembayaran->user->name, 0, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $pembayaran->user->name }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                            {{ $pembayaran->user->email }}
                                        </p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">
                                            Dicatat:
                                            {{ \Carbon\Carbon::parse($pembayaran->created_at)->translatedFormat('d M Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .payment-card {
                transition: all 0.3s ease;
            }

            .payment-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            }

            .status-indicator {
                animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }

            @keyframes pulse {

                0%,
                100% {
                    opacity: 1;
                }

                50% {
                    opacity: .5;
                }
            }
        </style>
    @endpush

</x-app-layout>
