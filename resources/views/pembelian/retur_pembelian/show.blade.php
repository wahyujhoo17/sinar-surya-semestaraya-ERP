<x-app-layout>
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center">
                    <div
                        class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-10 w-1 rounded-full mr-3">
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Detail Retur Pembelian
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ $returPembelian->nomor }}
                        </p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('pembelian.retur-pembelian.pdf', $returPembelian->id) }}" target="_blank"
                        class="group inline-flex items-center gap-2 rounded-lg px-4 py-2.5 bg-white dark:bg-gray-800 text-sm font-semibold text-gray-700 dark:text-gray-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 dark:text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span>Download PDF </span>
                    </a>

                    <a href="{{ route('pembelian.retur-pembelian.index') }}"
                        class="group inline-flex items-center gap-2 rounded-lg px-4 py-2.5 bg-gray-50 dark:bg-gray-800 text-sm font-semibold text-gray-700 dark:text-gray-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 dark:text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Kembali</span>
                    </a>


                    @if ($returPembelian->status === 'draft')
                        <a href="{{ route('pembelian.retur-pembelian.edit', $returPembelian->id) }}"
                            class="group inline-flex items-center gap-2 rounded-lg px-4 py-2.5 bg-amber-50 dark:bg-amber-900/30 text-sm font-semibold text-amber-700 dark:text-amber-400 shadow-sm ring-1 ring-inset ring-amber-600/20 dark:ring-amber-400/20 hover:bg-amber-100 dark:hover:bg-amber-900/40">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span>Edit</span>
                        </a>

                        <button type="button" onclick="openModal('prosesReturModal')"
                            class="group inline-flex items-center gap-2 rounded-lg px-4 py-2.5 bg-amber-50 dark:bg-amber-900/30 text-sm font-semibold text-amber-700 dark:text-amber-400 shadow-sm ring-1 ring-inset ring-amber-600/20 dark:ring-amber-400/20 hover:bg-amber-100 dark:hover:bg-amber-900/40">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                            <span>Proses</span>
                        </button>

                        <button type="button" onclick="openModal('hapusReturModal')"
                            class="group inline-flex items-center gap-2 rounded-lg px-4 py-2.5 bg-amber-50 dark:bg-amber-900/30 text-sm font-semibold text-amber-700 dark:text-amber-400 shadow-sm ring-1 ring-inset ring-amber-600/20 dark:ring-amber-400/20 hover:bg-amber-100 dark:hover:bg-amber-900/40">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span>Hapus</span>
                        </button>
                    @elseif($returPembelian->status === 'diproses')
                        <button type="button" onclick="openModal('selesaikanReturModal')"
                            class="group inline-flex items-center gap-2 rounded-lg px-4 py-2.5 bg-emerald-50 dark:bg-emerald-900/30 text-sm font-semibold text-emerald-700 dark:text-emerald-400 shadow-sm ring-1 ring-inset ring-emerald-600/20 dark:ring-emerald-400/20 hover:bg-emerald-100 dark:hover:bg-emerald-900/40">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Selesaikan</span>
                        </button>
                    @elseif($returPembelian->status === 'menunggu_barang_pengganti')
                        <a href="{{ route('pembelian.retur-pembelian.terima-barang-pengganti', $returPembelian->id) }}"
                            class="group inline-flex items-center gap-2 rounded-lg px-4 py-2.5 bg-purple-50 dark:bg-purple-900/30 text-sm font-semibold text-purple-700 dark:text-purple-400 shadow-sm ring-1 ring-inset ring-purple-600/20 dark:ring-purple-400/20 hover:bg-purple-100 dark:hover:bg-purple-900/40">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8 4m0 0l-8-4m8 4v10m-8-4l8 4 8-4" />
                            </svg>
                            <span>Terima Barang Pengganti</span>
                        </a>
                    @elseif(
                        $returPembelian->status === 'selesai' &&
                            isset($returPembelian->purchaseOrder) &&
                            $returPembelian->purchaseOrder->status_pembayaran === 'kelebihan_bayar' &&
                            $returPembelian->tipe_retur === 'pengembalian_dana')
                        <a href="{{ route('pembelian.retur-pembelian.create-refund', $returPembelian->id) }}"
                            class="group inline-flex items-center gap-2 rounded-lg px-4 py-2.5 bg-emerald-50 dark:bg-emerald-900/30 text-sm font-semibold text-emerald-700 dark:text-emerald-400 shadow-sm ring-1 ring-inset ring-emerald-600/20 dark:ring-emerald-400/20 hover:bg-emerald-100 dark:hover:bg-emerald-900/40">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Buat Pengembalian Dana</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Success and error messages --}}
        @if (session('success'))
            <div class="mb-6">
                <div class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 dark:border-green-400 p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700 dark:text-green-300">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6">
                <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 dark:border-red-400 p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 dark:text-red-300">
                                {{ session('error') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($returPembelian->status === 'selesai' && $returPembelian->tipe_retur === 'tukar_barang')
            <div class="mb-6">
                <div
                    class="bg-purple-50 dark:bg-purple-900/30 border-l-4 border-purple-500 dark:border-purple-400 p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-purple-400" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-purple-700 dark:text-purple-300">
                                Retur pembelian jenis tukar barang. Silakan koordinasikan dengan supplier untuk
                                penggantian barang.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (session('refundNeeded'))
            <div class="mb-6">
                <div class="bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 dark:border-blue-400 p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                Ada kelebihan pembayaran pada Purchase Order. Klik tombol "Buat Pengembalian Dana" untuk
                                mengembalikan dana ke supplier.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Breadcrumb --}}
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm font-medium">
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">Dashboard</a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </li>
                <li>
                    <a href="{{ route('pembelian.retur-pembelian.index') }}"
                        class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">Retur
                        Pembelian</a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </li>
                <li class="text-gray-800 dark:text-gray-100">
                    {{ $returPembelian->nomor }}
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left column - Header Info -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Retur</h2>

                    <div class="space-y-4">
                        <!-- Status Badge -->
                        <div class="mb-6">
                            @php
                                $statusColor = '';
                                $statusText = '';

                                switch ($returPembelian->status) {
                                    case 'draft':
                                        $statusColor = 'gray';
                                        $statusText = 'Draft';
                                        break;
                                    case 'diproses':
                                        $statusColor = 'blue';
                                        $statusText = 'Diproses';
                                        break;
                                    case 'menunggu_barang_pengganti':
                                        $statusColor = 'purple';
                                        $statusText = 'Menunggu Barang Pengganti';
                                        break;
                                    case 'selesai':
                                        $statusColor = 'emerald';
                                        $statusText = 'Selesai';
                                        break;
                                }
                            @endphp
                            <span
                                class="inline-flex items-center rounded-md bg-{{ $statusColor }}-100 dark:bg-{{ $statusColor }}-900/30 px-3 py-1 text-sm font-medium text-{{ $statusColor }}-700 dark:text-{{ $statusColor }}-400 ring-1 ring-inset ring-{{ $statusColor }}-600/20 dark:ring-{{ $statusColor }}-400/20">
                                {{ $statusText }}
                            </span>
                        </div>

                        <!-- Details -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                                <div class="grid grid-cols-3 gap-2 py-3">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor</dt>
                                    <dd class="col-span-2 text-sm text-gray-900 dark:text-white">
                                        {{ $returPembelian->nomor }}</dd>
                                </div>
                                <div class="grid grid-cols-3 gap-2 py-3">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</dt>
                                    <dd class="col-span-2 text-sm text-gray-900 dark:text-white">
                                        {{ $returPembelian->tanggal }}</dd>
                                </div>
                                <div class="grid grid-cols-3 gap-2 py-3">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Supplier</dt>
                                    <dd class="col-span-2 text-sm text-gray-900 dark:text-white">
                                        {{ $returPembelian->supplier->nama }}
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $returPembelian->supplier->email }}</div>
                                    </dd>
                                </div>
                                <div class="grid grid-cols-3 gap-2 py-3">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Purchase Order
                                    </dt>
                                    <dd class="col-span-2 text-sm text-gray-900 dark:text-white">
                                        {{ $returPembelian->purchaseOrder->nomor }}
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $returPembelian->purchaseOrder->tanggal }}</div>
                                    </dd>
                                </div>
                                {{-- Removed Gudang section as there's no relationship defined --}}
                                <div class="grid grid-cols-3 gap-2 py-3">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipe Retur</dt>
                                    <dd class="col-span-2 text-sm text-gray-900 dark:text-white">
                                        <span
                                            class="inline-flex items-center rounded-md px-2 py-1 text-sm font-medium {{ $returPembelian->tipe_retur === 'pengembalian_dana' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 ring-1 ring-inset ring-amber-600/20 dark:ring-amber-400/20' : 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400 ring-1 ring-inset ring-purple-600/20 dark:ring-purple-400/20' }}">
                                            {{ $returPembelian->tipe_retur === 'pengembalian_dana' ? 'Pengembalian Dana' : 'Tukar Barang' }}
                                        </span>
                                    </dd>
                                </div>

                                <div class="grid grid-cols-3 gap-2 py-3">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan</dt>
                                    <dd class="col-span-2 text-sm text-gray-900 dark:text-white">
                                        {{ $returPembelian->catatan ?: '-' }}
                                    </dd>
                                </div>
                                <div class="grid grid-cols-3 gap-2 py-3">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat Oleh</dt>
                                    <dd class="col-span-2 text-sm text-gray-900 dark:text-white">
                                        {{ $returPembelian->user ? $returPembelian->user->name : 'User tidak ditemukan' }}
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $returPembelian->created_at->format('d/m/Y H:i') }}</div>
                                    </dd>
                                </div>
                                @if ($returPembelian->updated_at->ne($returPembelian->created_at))
                                    <div class="grid grid-cols-3 gap-2 py-3">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Diubah
                                            Terakhir</dt>
                                        <dd class="col-span-2 text-sm text-gray-900 dark:text-white">
                                            {{-- Since there's no updatedBy relationship, we're just showing the timestamp --}}
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $returPembelian->updated_at->format('d/m/Y H:i') }}</div>
                                        </dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Activity Log -->
                @if (isset($activityLog) && is_countable($activityLog) && count($activityLog) > 0)
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden p-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Riwayat Aktivitas</h2>

                        <div class="flow-root">
                            <ul class="-mb-8">
                                @foreach ($activityLog as $log)
                                    <li>
                                        <div class="relative pb-8">
                                            @if (!$loop->last)
                                                <span
                                                    class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700"
                                                    aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex items-start space-x-3">
                                                <div class="relative">
                                                    @php
                                                        $iconColor = 'gray';

                                                        if (strpos($log->description, 'draft') !== false) {
                                                            $iconColor = 'gray';
                                                        } elseif (strpos($log->description, 'diproses') !== false) {
                                                            $iconColor = 'blue';
                                                        } elseif (strpos($log->description, 'selesai') !== false) {
                                                            $iconColor = 'emerald';
                                                        } elseif (strpos($log->description, 'create') !== false) {
                                                            $iconColor = 'indigo';
                                                        } elseif (strpos($log->description, 'update') !== false) {
                                                            $iconColor = 'amber';
                                                        } elseif (strpos($log->description, 'delete') !== false) {
                                                            $iconColor = 'red';
                                                        }
                                                    @endphp

                                                    <span
                                                        class="h-10 w-10 rounded-full bg-{{ $iconColor }}-100 dark:bg-{{ $iconColor }}-900/30 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                        @if (strpos($log->description, 'create') !== false)
                                                            <svg class="h-5 w-5 text-{{ $iconColor }}-600 dark:text-{{ $iconColor }}-400"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                            </svg>
                                                        @elseif(strpos($log->description, 'update') !== false)
                                                            <svg class="h-5 w-5 text-{{ $iconColor }}-600 dark:text-{{ $iconColor }}-400"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                            </svg>
                                                        @elseif(strpos($log->description, 'selesai') !== false)
                                                            <svg class="h-5 w-5 text-{{ $iconColor }}-600 dark:text-{{ $iconColor }}-400"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        @elseif(strpos($log->description, 'diproses') !== false)
                                                            <svg class="h-5 w-5 text-{{ $iconColor }}-600 dark:text-{{ $iconColor }}-400"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M9 5l7 7-7 7" />
                                                            </svg>
                                                        @elseif(strpos($log->description, 'delete') !== false)
                                                            <svg class="h-5 w-5 text-{{ $iconColor }}-600 dark:text-{{ $iconColor }}-400"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        @else
                                                            <svg class="h-5 w-5 text-{{ $iconColor }}-600 dark:text-{{ $iconColor }}-400"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                                            </svg>
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div>
                                                        <div class="text-sm text-gray-700 dark:text-gray-300">
                                                            <span
                                                                class="font-medium text-gray-900 dark:text-white">{{ $log->causer->name ?? 'System' }}</span>
                                                        </div>
                                                        <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $log->created_at->format('d M Y, H:i') }}
                                                        </p>
                                                    </div>
                                                    <div class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                                                        <p>{{ $log->description }}</p>
                                                        @if (isset($log->properties['attributes']) && count($log->properties['attributes']) > 0)
                                                            <div class="mt-2 text-xs">
                                                                @if (isset($log->properties['old']) && count($log->properties['old']) > 0)
                                                                    <span
                                                                        class="text-amber-600 dark:text-amber-400">Diubah:
                                                                        {{ implode(', ', array_keys($log->properties['old'])) }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right column - Items -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Detail Barang</h2>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th scope="col"
                                        class="px-3 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Produk
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Kuantitas
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Alasan
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Keterangan
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                                @foreach ($returPembelian->details ?? [] as $detail)
                                    <tr>
                                        <td class="px-3 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $detail->produk->nama }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                Kode: {{ $detail->produk->kode }}
                                            </div>
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ number_format($detail->quantity, 2, ',', '.') }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $detail->satuan->nama }}
                                            </div>
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $detail->alasan }}
                                            </div>
                                        </td>
                                        <td class="px-3 py-4">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $detail->keterangan ?: '-' }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                @if (!isset($returPembelian->details) || count($returPembelian->details) == 0)
                                    <tr>
                                        <td colspan="4"
                                            class="px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            Tidak ada detail barang untuk retur pembelian ini.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modals --}}
    <!-- Proses Retur Modal -->
    <div id="prosesReturModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closeModal('prosesReturModal')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                Proses Retur Pembelian
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Apakah Anda yakin ingin memproses retur pembelian ini? Status akan berubah menjadi
                                    "Diproses".
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form action="{{ route('pembelian.retur-pembelian.proses', $returPembelian->id) }}"
                        method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 dark:bg-blue-700 text-base font-medium text-white hover:bg-blue-700 dark:hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-blue-600 sm:ml-3 sm:w-auto sm:text-sm">
                            Proses
                        </button>
                    </form>
                    <button type="button" onclick="closeModal('prosesReturModal')"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:focus:ring-gray-400 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hapus Retur Modal -->
    <div id="hapusReturModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closeModal('hapusReturModal')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                Hapus Retur Pembelian
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Apakah Anda yakin ingin menghapus retur pembelian ini? Tindakan ini tidak dapat
                                    dibatalkan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form action="{{ route('pembelian.retur-pembelian.destroy', $returPembelian->id) }}"
                        method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 dark:bg-red-700 text-base font-medium text-white hover:bg-red-700 dark:hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-red-600 sm:ml-3 sm:w-auto sm:text-sm">
                            Hapus
                        </button>
                    </form>
                    <button type="button" onclick="closeModal('hapusReturModal')"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:focus:ring-gray-400 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Selesaikan Retur Modal -->
    <div id="selesaikanReturModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closeModal('selesaikanReturModal')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 dark:bg-emerald-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-emerald-600 dark:text-emerald-400"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                Selesaikan Retur Pembelian
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Apakah Anda yakin ingin menyelesaikan retur pembelian ini? Status akan berubah
                                    menjadi "Selesai" dan stok barang akan dikurangi.
                                </p>
                                <div class="mt-4">
                                    <label for="gudang_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih
                                        Gudang</label>
                                    <select id="gudang_id" name="gudang_id" form="selesaikanForm"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-700 dark:bg-gray-800 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
                                        required>
                                        @foreach ($gudangs as $gudang)
                                            <option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form id="selesaikanForm"
                        action="{{ route('pembelian.retur-pembelian.selesai', $returPembelian->id) }}"
                        method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 dark:bg-emerald-700 text-base font-medium text-white hover:bg-emerald-700 dark:hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 dark:focus:ring-emerald-600 sm:ml-3 sm:w-auto sm:text-sm">
                            Selesaikan
                        </button>
                    </form>
                    <button type="button" onclick="closeModal('selesaikanReturModal')"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:focus:ring-gray-400 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Modal functions
            function openModal(modalId) {
                document.getElementById(modalId).classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeModal(modalId) {
                document.getElementById(modalId).classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                const modals = document.querySelectorAll('[id$="Modal"]');
                modals.forEach(function(modal) {
                    if (event.target === modal) {
                        closeModal(modal.id);
                    }
                });
            });

            // Close modal with ESC key
            window.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    const openModal = document.querySelector('[id$="Modal"]:not(.hidden)');
                    if (openModal) {
                        closeModal(openModal.id);
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
