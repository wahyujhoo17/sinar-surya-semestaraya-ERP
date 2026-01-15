<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="'Kalibrasi Persediaan'">
    <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-indigo-600 dark:text-indigo-400"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                Kalibrasi Nilai Persediaan
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Sinkronisasi nilai persediaan di akuntansi (1120) dengan nilai fisik di gudang untuk perhitungan HPP
                yang
                akurat
            </p>
        </div>

        {{-- Alert Messages --}}
        @if (session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 p-4 rounded-md">
                <div class="flex">
                    <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="ml-3 text-sm text-green-700 dark:text-green-400">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('info'))
            <div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-4 rounded-md">
                <div class="flex">
                    <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="ml-3 text-sm text-blue-700 dark:text-blue-400">{{ session('info') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded-md">
                <div class="flex">
                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="ml-3">
                        @foreach ($errors->all() as $error)
                            <p class="text-sm text-red-700 dark:text-red-400">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- Warning: Produk Tanpa Harga --}}
        @if (isset($produkTanpaHarga) && $produkTanpaHarga->count() > 0)
            <div class="mb-6 bg-orange-50 dark:bg-orange-900/20 border-l-4 border-orange-500 p-4 rounded-md">
                <div class="flex">
                    <svg class="h-6 w-6 text-orange-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="ml-3 flex-1">
                        <h3 class="text-sm font-semibold text-orange-800 dark:text-orange-400">
                            ⚠️ Peringatan: {{ $produkTanpaHarga->count() }} Produk Tanpa Harga Beli
                        </h3>
                        <p class="mt-2 text-sm text-orange-700 dark:text-orange-300">
                            Produk berikut memiliki stok tetapi <strong>belum memiliki harga beli</strong>. Nilai
                            persediaan
                            untuk produk ini akan dihitung <strong>Rp 0</strong> dan menyebabkan kalkulasi tidak akurat:
                        </p>
                        <div
                            class="mt-3 max-h-40 overflow-y-auto bg-white dark:bg-gray-800 rounded border border-orange-200 dark:border-orange-700">
                            <ul class="divide-y divide-orange-100 dark:divide-orange-800">
                                @foreach ($produkTanpaHarga as $stok)
                                    <li class="px-3 py-2 text-xs">
                                        <span class="font-medium text-orange-900 dark:text-orange-300">
                                            {{ $stok->produk->kode ?? '-' }}
                                        </span>
                                        <span class="text-orange-700 dark:text-orange-400">
                                            - {{ $stok->produk->nama ?? '-' }}
                                        </span>
                                        <span class="text-orange-600 dark:text-orange-500">
                                            (Stok: {{ number_format($stok->jumlah, 2) }}
                                            {{ $stok->produk->satuan->nama ?? 'pcs' }})
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <p class="mt-3 text-sm text-orange-800 dark:text-orange-300">
                            <strong>Rekomendasi:</strong> Update harga beli di
                            <a href="{{ route('master.produk.index') }}"
                                class="underline font-semibold hover:text-orange-900 dark:hover:text-orange-200">
                                Master Data Produk
                            </a>
                            sebelum melakukan kalibrasi persediaan.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            {{-- Nilai Persediaan Akuntansi --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Nilai Akuntansi</p>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                            @if ($akunPersediaan)
                                {{ $akunPersediaan->kode }} - {{ $akunPersediaan->nama }}
                            @else
                                Belum dikonfigurasi
                            @endif
                        </p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                            Rp {{ number_format($nilaiPersediaanAkuntansi, 0, ',', '.') }}
                        </p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-500 opacity-50" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>

            {{-- Nilai Persediaan Fisik --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Nilai Fisik Gudang</p>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Berdasarkan stok aktual</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                            Rp {{ number_format($nilaiPersediaanFisik, 0, ',', '.') }}
                        </p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-500 opacity-50" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>

            {{-- Selisih --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 {{ abs($selisih) < 0.01 ? 'border-gray-500' : ($selisih > 0 ? 'border-orange-500' : 'border-red-500') }}">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Selisih</p>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                            @if (abs($selisih) < 0.01)
                                <span class="text-green-600">✓ Sudah sesuai</span>
                            @elseif($selisih > 0)
                                <span class="text-orange-600">Fisik > Akuntansi</span>
                            @else
                                <span class="text-red-600">Fisik < Akuntansi</span>
                            @endif
                        </p>
                        <p
                            class="text-2xl font-bold mt-2 {{ abs($selisih) < 0.01 ? 'text-gray-500' : ($selisih > 0 ? 'text-orange-600' : 'text-red-600') }}">
                            Rp {{ number_format(abs($selisih), 0, ',', '.') }}
                        </p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-12 w-12 {{ abs($selisih) < 0.01 ? 'text-gray-500' : ($selisih > 0 ? 'text-orange-500' : 'text-red-500') }} opacity-50"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg shadow-md p-6 text-white">
                <p class="text-sm font-medium mb-3">Tindakan</p>

                @if (abs($selisih) > 0.01)
                    <button onclick="openSyncModal()" type="button"
                        class="w-full bg-white text-indigo-600 hover:bg-gray-100 px-4 py-2 rounded-lg font-semibold transition duration-200 shadow-md mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Sinkronkan Sekarang
                    </button>
                @else
                    <div class="bg-white bg-opacity-20 rounded-lg p-3 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm">Data sudah sesuai</p>
                        <p class="text-xs mt-1">Tidak perlu sinkronisasi</p>
                    </div>
                @endif
                <a href="{{ route('keuangan.jurnal-penyesuaian-persediaan.history') }}"
                    class="block w-full bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-lg font-semibold transition duration-200 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Lihat History
                </a>
            </div>
        </div>

        {{-- Detail Table --}}
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Persediaan per Produk</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Total {{ count($detailProduk) }} produk dengan nilai persediaan
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Kode
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Nama Produk
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Gudang
                            </th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Qty
                            </th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Harga Pokok
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Sumber Harga
                            </th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Nilai Total
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($detailProduk as $detail)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $detail['kode_produk'] }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    {{ $detail['nama_produk'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                    {{ $detail['gudang'] }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                                    {{ number_format($detail['qty'], 2) }} {{ $detail['satuan'] }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                                    Rp {{ number_format($detail['harga_pokok'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                                    <span
                                        class="px-2 py-1 rounded-full {{ $detail['sumber_harga'] == 'Harga Beli Rata-rata' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                        {{ $detail['sumber_harga'] }}
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-gray-900 dark:text-white">
                                    Rp {{ number_format($detail['nilai_total'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-12 w-12 mx-auto mb-4 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="text-lg font-medium">Tidak ada data persediaan</p>
                                    <p class="text-sm mt-1">Belum ada produk dengan stok di gudang</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if (count($detailProduk) > 0)
                        <tfoot class="bg-gray-100 dark:bg-gray-900">
                            <tr>
                                <td colspan="6"
                                    class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white">
                                    Total Nilai Persediaan:
                                </td>
                                <td
                                    class="px-6 py-4 text-right font-bold text-lg text-indigo-600 dark:text-indigo-400">
                                    Rp {{ number_format($nilaiPersediaanFisik, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>

        {{-- Information Box --}}
        <div class="mt-8 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
            <div class="flex">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 text-blue-600 dark:text-blue-400 mr-3 flex-shrink-0" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-300 mb-2">Informasi Penting:</h4>
                    <ul class="text-sm text-blue-800 dark:text-blue-400 space-y-1 list-disc list-inside">
                        <li>Nilai persediaan akuntansi diambil dari saldo akun
                            <strong>{{ $akunPersediaan ? $akunPersediaan->kode . ' - ' . $akunPersediaan->nama : 'Belum dikonfigurasi' }}</strong>
                        </li>
                        <li>Nilai persediaan fisik dihitung dari <strong>Qty × Harga Beli Rata-rata</strong> (atau Harga
                            Beli/Harga Pokok jika rata-rata tidak tersedia)</li>
                        <li>Fitur sinkronisasi akan membuat jurnal penyesuaian persediaan secara otomatis</li>
                        <li>Proses ini hanya perlu dilakukan untuk <strong>setup awal</strong> atau <strong>koreksi
                                manual</strong></li>
                        <li>Setelah sinkronisasi awal, sistem <strong>perpetual</strong> akan otomatis update nilai
                            persediaan saat pembelian dan penjualan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Sync --}}
    <div id="syncModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div
            class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Konfirmasi Sinkronisasi Persediaan
                    </h3>
                    <button onclick="closeSyncModal()"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form action="{{ route('keuangan.jurnal-penyesuaian-persediaan.sync') }}" method="POST">
                    @csrf
                    <div
                        class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-4">
                        <div class="flex">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600 mr-2"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div class="text-sm text-yellow-800 dark:text-yellow-400">
                                <p class="font-semibold mb-1">Jurnal penyesuaian akan dibuat:</p>
                                @if ($selisih > 0)
                                    <p>• <strong>Debit:</strong>
                                        {{ $akunPersediaan ? $akunPersediaan->kode . ' - ' . $akunPersediaan->nama : 'Persediaan' }}
                                        - Rp
                                        {{ number_format($selisih, 0, ',', '.') }}</p>
                                    <p>• <strong>Kredit:</strong> Penyesuaian Persediaan - Rp
                                        {{ number_format($selisih, 0, ',', '.') }}</p>
                                @else
                                    <p>• <strong>Debit:</strong> Penyesuaian Persediaan - Rp
                                        {{ number_format(abs($selisih), 0, ',', '.') }}</p>
                                    <p>• <strong>Kredit:</strong>
                                        {{ $akunPersediaan ? $akunPersediaan->kode . ' - ' . $akunPersediaan->nama : 'Persediaan' }}
                                        - Rp
                                        {{ number_format(abs($selisih), 0, ',', '.') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tanggal Jurnal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal" id="tanggal" required value="{{ date('Y-m-d') }}"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="mb-4">
                        <label for="keterangan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Keterangan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="keterangan" id="keterangan" rows="3" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Contoh: Kalibrasi persediaan awal sistem...">Kalibrasi persediaan - Penyesuaian nilai persediaan akuntansi dengan nilai fisik gudang</textarea>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" onclick="closeSyncModal()"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Sinkronkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openSyncModal() {
                const modal = document.getElementById('syncModal');
                if (modal) {
                    modal.classList.remove('hidden');
                }
            }

            function closeSyncModal() {
                const modal = document.getElementById('syncModal');
                if (modal) {
                    modal.classList.add('hidden');
                }
            }

            // Close modal on ESC key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeSyncModal();
                }
            });

            // Close modal on outside click
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('syncModal');
                if (modal) {
                    modal.addEventListener('click', function(event) {
                        if (event.target === this) {
                            closeSyncModal();
                        }
                    });
                } else {
                    console.error('Modal element not found on DOMContentLoaded!');
                }
            });
        </script>
    @endpush
</x-app-layout>
