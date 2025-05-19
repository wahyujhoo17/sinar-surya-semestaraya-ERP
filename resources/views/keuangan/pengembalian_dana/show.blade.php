<x-app-layout>
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-gradient-to-br from-blue-500 to-blue-600 h-10 w-1 rounded-full mr-3">
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Detail Pengembalian Dana
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ $refund->nomor }}
                        </p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('keuangan.pengembalian-dana.print', $refund->id) }}" target="_blank"
                        class="group inline-flex items-center gap-2 rounded-lg px-4 py-2.5 bg-white dark:bg-gray-800 text-sm font-semibold text-gray-700 dark:text-gray-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 dark:text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        <span>Download Bukti</span>
                    </a>

                    <a href="{{ route('keuangan.pengembalian-dana.index') }}"
                        class="group inline-flex items-center gap-2 rounded-lg px-4 py-2.5 bg-gray-50 dark:bg-gray-800 text-sm font-semibold text-gray-700 dark:text-gray-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 dark:text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Kembali</span>
                    </a>

                    <a href="{{ route('keuangan.pengembalian-dana.edit', $refund->id) }}"
                        class="group inline-flex items-center gap-2 rounded-lg px-4 py-2.5 bg-amber-50 dark:bg-amber-900/30 text-sm font-semibold text-amber-700 dark:text-amber-400 shadow-sm ring-1 ring-inset ring-amber-600/20 dark:ring-amber-400/20 hover:bg-amber-100 dark:hover:bg-amber-900/40">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Edit</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Breadcrumb --}}
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm font-medium">
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Dashboard</a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </li>
                <li>
                    <a href="{{ route('keuangan.pengembalian-dana.index') }}"
                        class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Pengembalian
                        Dana</a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </li>
                <li class="text-gray-800 dark:text-gray-100">
                    {{ $refund->nomor }}
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left column - Basic Information -->
            <div class="lg:col-span-1 space-y-6">
                @if (session('success'))
                    <div
                        class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 dark:border-green-400 p-4 mb-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-600 dark:text-green-400"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
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
                @endif

                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Dasar</h2>
                    <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div class="grid grid-cols-3 gap-2 py-3">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor</dt>
                            <dd class="col-span-2 text-sm text-gray-900 dark:text-white">
                                {{ $refund->nomor }}
                            </dd>
                        </div>
                        <div class="grid grid-cols-3 gap-2 py-3">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</dt>
                            <dd class="col-span-2 text-sm text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($refund->tanggal)->format('d/m/Y') }}
                            </dd>
                        </div>
                        <div class="grid grid-cols-3 gap-2 py-3">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Supplier</dt>
                            <dd class="col-span-2 text-sm text-gray-900 dark:text-white">
                                {{ $refund->supplier->nama }}
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $refund->supplier->email }}
                                </div>
                            </dd>
                        </div>
                        <div class="grid grid-cols-3 gap-2 py-3">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Purchase Order</dt>
                            <dd class="col-span-2 text-sm text-gray-900 dark:text-white">
                                <a href="{{ route('pembelian.purchasing-order.show', $refund->purchaseOrder->id) }}"
                                    class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $refund->purchaseOrder->nomor }}
                                </a>
                            </dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Keuangan</h2>
                    <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div class="grid grid-cols-3 gap-2 py-3">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah</dt>
                            <dd class="col-span-2 text-sm font-semibold text-gray-900 dark:text-white">
                                Rp {{ number_format($refund->jumlah, 0, ',', '.') }}
                            </dd>
                        </div>
                        <div class="grid grid-cols-3 gap-2 py-3">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Metode Penerimaan</dt>
                            <dd class="col-span-2 text-sm text-gray-900 dark:text-white">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $refund->metode_penerimaan == 'kas' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' }}">
                                    {{ ucfirst($refund->metode_penerimaan) }}
                                </span>
                            </dd>
                        </div>
                        <div class="grid grid-cols-3 gap-2 py-3">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Akun</dt>
                            <dd class="col-span-2 text-sm text-gray-900 dark:text-white">
                                @if ($refund->metode_penerimaan == 'kas')
                                    {{ $refund->kas->nama ?? '-' }}
                                @elseif($refund->metode_penerimaan == 'bank')
                                    {{ $refund->rekeningBank->nama_bank ?? '-' }} -
                                    {{ $refund->rekeningBank->nomor ?? '-' }}
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $refund->rekeningBank->atas_nama ?? '-' }}
                                    </div>
                                @else
                                    -
                                @endif
                            </dd>
                        </div>
                        <div class="grid grid-cols-3 gap-2 py-3">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">No. Referensi</dt>
                            <dd class="col-span-2 text-sm text-gray-900 dark:text-white">
                                {{ $refund->no_referensi ?? '-' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Right column - Notes and Additional Info -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Catatan</h2>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">
                            {{ $refund->catatan ?? 'Tidak ada catatan' }}
                        </p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Tambahan</h2>
                    <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div class="grid grid-cols-3 gap-2 py-3">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat Oleh</dt>
                            <dd class="col-span-2 text-sm text-gray-900 dark:text-white">
                                {{ $refund->user->name ?? '-' }}
                            </dd>
                        </div>
                        <div class="grid grid-cols-3 gap-2 py-3">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat Pada</dt>
                            <dd class="col-span-2 text-sm text-gray-900 dark:text-white">
                                {{ $refund->created_at->format('d/m/Y H:i:s') }}
                            </dd>
                        </div>
                        <div class="grid grid-cols-3 gap-2 py-3">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diubah</dt>
                            <dd class="col-span-2 text-sm text-gray-900 dark:text-white">
                                {{ $refund->updated_at->format('d/m/Y H:i:s') }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <div class="flex justify-between mt-6">
                    <div></div>
                    <form action="{{ route('keuangan.pengembalian-dana.destroy', $refund->id) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengembalian dana ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
