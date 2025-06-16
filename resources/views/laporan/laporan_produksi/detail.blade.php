<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div
            class="mb-6 bg-gradient-to-r from-white to-gray-50 dark:from-gray-800 dark:to-gray-800/70 rounded-xl shadow p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <div class="flex-shrink-0 bg-gradient-to-br from-blue-500 to-indigo-600 h-9 w-1.5 rounded-full">
                        </div>
                        Detail Produksi #{{ $produksi->nomor }}
                    </h1>
                    <p class="mt-2 text-sm md:text-base text-gray-600 dark:text-gray-400 max-w-3xl">
                        Detail lengkap dari data produksi dan status terkini.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('laporan.produksi.detail.pdf', $produksi->id) }}" target="_blank"
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <path d="M12 18v-6"></path>
                            <path d="M8 15l4 4 4-4"></path>
                        </svg>
                        Unduh PDF
                    </a>
                    <a href="{{ route('laporan.produksi.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition-all duration-200 shadow-sm focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 12H5"></path>
                            <path d="M12 19l-7-7 7-7"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Data Produksi --}}
            <div
                class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/60">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Informasi Produksi
                    </h3>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Produksi</dt>
                                    <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                        <a href="{{ route('produksi.work-order.show', $produksi->id) }}"
                                            class="text-blue-500 hover:text-blue-700">
                                            {{ $produksi->nomor }}
                                        </a>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Produksi
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($produksi->tanggal)->format('d M Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                    <dd class="mt-1">
                                        @if ($produksi->status == 'selesai')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-700/20 dark:text-green-500">
                                                Selesai
                                            </span>
                                        @elseif($produksi->status == 'proses')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-700/20 dark:text-blue-500">
                                                Proses
                                            </span>
                                        @elseif($produksi->status == 'batal')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-700/20 dark:text-red-500">
                                                Dibatalkan
                                            </span>
                                        @elseif($produksi->status == 'menunggu')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-700/20 dark:text-yellow-500">
                                                Menunggu
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Produk</dt>
                                    <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $produksi->produk_nama }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kode Produk</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $produksi->produk_kode }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Produksi
                                    </dt>
                                    <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ number_format($produksi->jumlah, 0, ',', '.') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    @if ($produksi->catatan)
                        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan:</h4>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white whitespace-pre-line">
                                {{ $produksi->catatan }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Informasi Tambahan --}}
            <div
                class="lg:col-span-1 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/60">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Informasi Tambahan
                    </h3>
                </div>
                <div class="p-5">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Petugas</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $produksi->nama_petugas ?? '-' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Dibuat</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ isset($produksi->created_at) ? \Carbon\Carbon::parse($produksi->created_at)->format('d M Y H:i') : '-' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diupdate</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ isset($produksi->updated_at) ? \Carbon\Carbon::parse($produksi->updated_at)->format('d M Y H:i') : '-' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
