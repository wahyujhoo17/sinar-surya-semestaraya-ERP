<x-app-layout :breadcrumbs="[
    ['name' => 'Keuangan', 'url' => '#'],
    ['name' => 'Uang Muka Penjualan', 'url' => route('keuangan.uang-muka-penjualan.index')],
    ['name' => 'Detail Uang Muka', 'url' => '#'],
]" :currentPage="'Detail Uang Muka Penjualan - ' . $uangMuka->nomor">

    <div class="w-full max-w-none py-6 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-none mx-auto">
            {{-- Header Section --}}
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center space-x-4">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-blue-500 to-indigo-600 h-12 w-1.5 rounded-full mr-4">
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Detail Uang Muka Penjualan</h1>
                            <p class="mt-1 text-lg text-gray-600 dark:text-gray-400">
                                Nomor: <span
                                    class="font-semibold text-blue-600 dark:text-blue-400">{{ $uangMuka->nomor }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        @if ($uangMuka->status === 'draft')
                            <a href="{{ route('keuangan.uang-muka-penjualan.edit', $uangMuka->id) }}"
                                class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-xl text-sm font-medium text-white bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                Edit
                            </a>
                        @endif

                        <a href="{{ route('keuangan.uang-muka-penjualan.exportPdf', $uangMuka->id) }}" target="_blank"
                            class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-xl text-sm font-medium text-white bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Export PDF
                        </a>

                        <a href="{{ route('keuangan.uang-muka-penjualan.index') }}"
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
                {{-- Left Column - Details --}}
                <div class="xl:col-span-2 space-y-6">
                    {{-- Basic Information Card --}}
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
                                Informasi Uang Muka
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nomor
                                            Uang Muka</dt>
                                        <dd class="text-base font-semibold text-gray-900 dark:text-white">
                                            {{ $uangMuka->nomor }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal
                                        </dt>
                                        <dd class="text-base text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($uangMuka->tanggal)->translatedFormat('d F Y') }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Status
                                        </dt>
                                        <dd class="text-base text-gray-900 dark:text-white">
                                            @php
                                                $badgeClasses = match ($uangMuka->status) {
                                                    'confirmed'
                                                        => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
                                                    'draft'
                                                        => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
                                                    'cancelled'
                                                        => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400',
                                                    default
                                                        => 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400',
                                                };
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $badgeClasses }}">
                                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                    @if ($uangMuka->status === 'confirmed')
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd"></path>
                                                    @elseif($uangMuka->status === 'draft')
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                                            clip-rule="evenodd"></path>
                                                    @elseif($uangMuka->status === 'cancelled')
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                            clip-rule="evenodd"></path>
                                                    @endif
                                                </svg>
                                                {{ ucfirst($uangMuka->status) }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Customer
                                        </dt>
                                        <dd class="text-base font-semibold text-gray-900 dark:text-white">
                                            {{ $uangMuka->customer->nama ?: $uangMuka->customer->company }}
                                        </dd>
                                        @if ($uangMuka->customer->nama && $uangMuka->customer->company)
                                            <dd class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $uangMuka->customer->company }}
                                            </dd>
                                        @endif
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Jumlah
                                            Uang Muka</dt>
                                        <dd class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                            Rp {{ number_format($uangMuka->jumlah, 0, ',', '.') }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Jumlah
                                            Tersedia</dt>
                                        <dd class="text-xl font-semibold text-green-600 dark:text-green-400">
                                            Rp {{ number_format($uangMuka->jumlah_tersedia, 0, ',', '.') }}
                                        </dd>
                                    </div>
                                    @if ($uangMuka->sales_order_id)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Sales
                                                Order</dt>
                                            <dd class="text-base text-gray-900 dark:text-white">
                                                <a href="{{ route('penjualan.sales-order.show', $uangMuka->sales_order_id) }}"
                                                    class="text-blue-600 dark:text-blue-400 hover:underline">
                                                    {{ $uangMuka->salesOrder->nomor ?? '-' }}
                                                </a>
                                            </dd>
                                        </div>
                                    @endif
                                    @if ($uangMuka->nomor_referensi)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nomor
                                                Referensi</dt>
                                            <dd class="text-base text-gray-900 dark:text-white">
                                                {{ $uangMuka->nomor_referensi }}
                                            </dd>
                                        </div>
                                    @endif
                                </div>
                                @if ($uangMuka->keterangan)
                                    <div class="sm:col-span-2">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Keterangan
                                        </dt>
                                        <dd class="text-base text-gray-900 dark:text-white">
                                            {{ $uangMuka->keterangan }}
                                        </dd>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

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
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Metode
                                        Pembayaran</dt>
                                    <dd class="text-base text-gray-900 dark:text-white">
                                        @php
                                            $paymentBadgeClasses = match ($uangMuka->metode_pembayaran) {
                                                'bank'
                                                    => 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400',
                                                'kas'
                                                    => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
                                                default
                                                    => 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400',
                                            };
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $paymentBadgeClasses }}">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                @if ($uangMuka->metode_pembayaran === 'bank')
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                                    </path>
                                                @elseif($uangMuka->metode_pembayaran === 'kas')
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                                    </path>
                                                @endif
                                            </svg>
                                            {{ $uangMuka->metode_pembayaran === 'kas' ? 'Kas' : 'Bank Transfer' }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        {{ $uangMuka->metode_pembayaran === 'kas' ? 'Kas' : 'Rekening Bank' }}
                                    </dt>
                                    <dd class="text-base text-gray-900 dark:text-white">
                                        @if ($uangMuka->metode_pembayaran === 'kas' && $uangMuka->kas)
                                            {{ $uangMuka->kas->nama }}
                                        @elseif($uangMuka->metode_pembayaran === 'bank' && $uangMuka->rekeningBank)
                                            {{ $uangMuka->rekeningBank->nama_bank }} -
                                            {{ $uangMuka->rekeningBank->nomor_rekening }}
                                        @else
                                            -
                                        @endif
                                    </dd>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Applications History Card --}}
                    @if ($uangMuka->aplikasi->count() > 0)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <div
                                class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                        </path>
                                    </svg>
                                    Riwayat Aplikasi ke Invoice
                                    <span
                                        class="ml-2 text-sm bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 px-2 py-1 rounded-full">
                                        {{ $uangMuka->aplikasi->count() }} aplikasi
                                    </span>
                                </h3>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    @foreach ($uangMuka->aplikasi as $aplikasi)
                                        <div
                                            class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-600 rounded-xl">
                                            <div class="flex items-start space-x-4">
                                                <div class="flex-shrink-0">
                                                    <div
                                                        class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                        Invoice:
                                                        <a href="{{ route('penjualan.invoice.show', $aplikasi->invoice_id) }}"
                                                            class="text-purple-600 dark:text-purple-400 hover:underline">
                                                            {{ $aplikasi->invoice->nomor }}
                                                        </a>
                                                    </p>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                                        Tanggal:
                                                        {{ \Carbon\Carbon::parse($aplikasi->tanggal_aplikasi)->translatedFormat('d M Y') }}
                                                    </p>
                                                    @if ($aplikasi->keterangan)
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                            {{ $aplikasi->keterangan }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-lg font-semibold text-purple-600 dark:text-purple-400">
                                                    Rp {{ number_format($aplikasi->jumlah_aplikasi, 0, ',', '.') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- System Information Card --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div
                            class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-slate-50 dark:from-gray-900/20 dark:to-slate-900/20">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Informasi Sistem
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Dibuat Oleh
                                    </dt>
                                    <dd class="text-base text-gray-900 dark:text-white">
                                        {{ $uangMuka->user->name ?? 'Sistem' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Dibuat Pada
                                    </dt>
                                    <dd class="text-base text-gray-900 dark:text-white">
                                        {{ $uangMuka->created_at->translatedFormat('d F Y H:i') }}
                                    </dd>
                                </div>
                                @if ($uangMuka->updated_at != $uangMuka->created_at)
                                    <div class="sm:col-span-2">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Terakhir
                                            Diupdate</dt>
                                        <dd class="text-base text-gray-900 dark:text-white">
                                            {{ $uangMuka->updated_at->translatedFormat('d F Y H:i') }}
                                        </dd>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column - Summary & Actions --}}
                <div class="xl:col-span-1 space-y-6">
                    {{-- Financial Summary Card --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden sticky top-6">
                        <div
                            class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Ringkasan Keuangan
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div
                                    class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-700">
                                    <dt class="text-sm text-gray-600 dark:text-gray-400">Jumlah Awal:</dt>
                                    <dd class="text-lg font-bold text-gray-900 dark:text-white">
                                        Rp {{ number_format($uangMuka->jumlah, 0, ',', '.') }}
                                    </dd>
                                </div>
                                <div
                                    class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-700">
                                    <dt class="text-sm text-gray-600 dark:text-gray-400">Total Digunakan:</dt>
                                    <dd class="text-lg font-semibold text-red-600 dark:text-red-400">
                                        Rp
                                        {{ number_format($uangMuka->aplikasi->sum('jumlah_aplikasi'), 0, ',', '.') }}
                                    </dd>
                                </div>
                                <div class="flex justify-between items-center py-3">
                                    <dt class="text-sm text-gray-600 dark:text-gray-400">Saldo Tersedia:</dt>
                                    <dd class="text-xl font-bold text-green-600 dark:text-green-400">
                                        Rp {{ number_format($uangMuka->jumlah_tersedia, 0, ',', '.') }}
                                    </dd>
                                </div>
                            </div>

                            {{-- Progress Bar --}}
                            @php
                                $percentage =
                                    $uangMuka->jumlah > 0
                                        ? (($uangMuka->jumlah - $uangMuka->jumlah_tersedia) / $uangMuka->jumlah) * 100
                                        : 0;
                            @endphp
                            <div class="mt-6">
                                <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                                    <span>Penggunaan</span>
                                    <span>{{ number_format($percentage, 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-blue-500 to-indigo-500 h-3 rounded-full transition-all duration-300"
                                        style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>

                            {{-- Quick Apply Action --}}
                            @if ($uangMuka->status === 'confirmed' && $uangMuka->jumlah_tersedia > 0)
                                <div
                                    class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
                                    <h4 class="text-sm font-medium text-blue-900 dark:text-blue-300 mb-2">💡 Aksi
                                        Cepat:</h4>
                                    <p class="text-xs text-blue-800 dark:text-blue-300 mb-3">
                                        Uang muka ini dapat diaplikasikan ke invoice customer yang sama
                                    </p>
                                    <button onclick="showApplyModal()"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Aplikasikan ke Invoice
                                    </button>
                                </div>
                            @endif

                            {{-- Status Info --}}
                            <div
                                class="mt-6 p-4 bg-gray-50 dark:bg-gray-900/20 rounded-xl border border-gray-200 dark:border-gray-700">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-300 mb-2">ℹ️ Informasi:
                                </h4>
                                <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                                    @if ($uangMuka->status === 'confirmed')
                                        <li>• Uang muka telah dikonfirmasi</li>
                                        <li>• Dapat diaplikasikan ke invoice</li>
                                    @elseif($uangMuka->status === 'draft')
                                        <li>• Status masih draft</li>
                                        <li>• Belum dapat digunakan</li>
                                    @elseif($uangMuka->status === 'cancelled')
                                        <li>• Uang muka telah dibatalkan</li>
                                        <li>• Tidak dapat digunakan</li>
                                    @endif
                                    <li>• Jurnal otomatis saat konfirmasi</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Apply to Invoice Modal (placeholder) --}}
    <div id="applyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Aplikasikan ke Invoice</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Fitur ini akan segera tersedia untuk mengaplikasikan uang muka ke invoice customer.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <button onclick="hideApplyModal()"
                        class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function showApplyModal() {
                document.getElementById('applyModal').classList.remove('hidden');
            }

            function hideApplyModal() {
                document.getElementById('applyModal').classList.add('hidden');
            }
        </script>
    @endpush

</x-app-layout>
