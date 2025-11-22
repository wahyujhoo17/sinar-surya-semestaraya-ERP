<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Header with Product Name --}}
        <div
            class="mb-6 bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-800/70 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-12 w-1.5 rounded-full">
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                            {{ $produk->nama }}
                        </h1>
                        <div class="mt-2 flex items-center gap-3">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-400">
                                Kode: {{ $produk->kode }}
                            </span>
                            @if ($produk->kategori)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400">
                                    {{ $produk->kategori->nama }}
                                </span>
                            @endif
                            @if ($produk->satuan)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400">
                                    Satuan: {{ $produk->satuan->nama }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center">
                    <a href="{{ route('laporan.stok.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg border border-gray-300 dark:border-gray-600 text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        {{-- Current Stock Summary --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Stock Overview Card -->
            <div
                class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div
                    class="border-b border-gray-200 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-800/80 px-6 py-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-500" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path
                                d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z" />
                        </svg>
                        Stok per Gudang
                    </h2>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-700/50">
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Gudang</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Stok</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Lokasi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($stokSaatIni as $stokItem)
                                    <tr
                                        class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150">
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-medium">
                                            {{ $stokItem->gudang->nama ?? 'Tidak Ada Gudang' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center">
                                                <span
                                                    class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ isset($stokItem->jumlah) && $stokItem->jumlah < $produk->stok_minimum ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' }}">
                                                    {{ number_format($stokItem->jumlah ?? 0, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $stokItem->lokasi_rak ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3"
                                            class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            Tidak ada data stok
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Filter Card -->
            <div
                class="lg:col-span-1 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-800/80 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-500" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                clip-rule="evenodd" />
                        </svg>
                        Filter Riwayat
                    </h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('laporan.stok.detail', [$produk->id, $gudangId]) }}" method="GET"
                        class="space-y-4">
                        <div class="space-y-2">
                            <label for="gudang-filter"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Gudang
                            </label>
                            <select name="gudang_id" id="gudang-filter"
                                class="form-select block w-full rounded-lg shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 focus:ring focus:ring-primary-500/20 focus:border-primary-500 transition-colors duration-200">
                                <option value="">Semua Gudang</option>
                                @foreach ($gudangList as $gudang)
                                    <option value="{{ $gudang->id }}"
                                        {{ $gudangId == $gudang->id ? 'selected' : '' }}>
                                        {{ $gudang->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="date-start" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Tanggal Mulai
                            </label>
                            <input type="date" id="date-start" name="tanggal_mulai"
                                value="{{ $tanggalMulai->format('Y-m-d') }}"
                                class="form-input block w-full rounded-lg shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 focus:ring focus:ring-primary-500/20 focus:border-primary-500 transition-colors duration-200">
                        </div>

                        <div class="space-y-2">
                            <label for="date-end" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Tanggal Akhir
                            </label>
                            <input type="date" id="date-end" name="tanggal_akhir"
                                value="{{ $tanggalAkhir->format('Y-m-d') }}"
                                class="form-input block w-full rounded-lg shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 focus:ring focus:ring-primary-500/20 focus:border-primary-500 transition-colors duration-200">
                        </div>

                        <div class="pt-4">
                            <button type="submit"
                                class="w-full flex justify-center items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                        clip-rule="evenodd" />
                                </svg>
                                Terapkan Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Stock History Table --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-800/80 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-500" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                            clip-rule="evenodd" />
                    </svg>
                    Riwayat Perubahan Stok
                </h2>
            </div>

            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                No</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                Tanggal</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Gudang</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Jenis</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Sebelum</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Perubahan</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Setelah</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Referensi</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($riwayat as $index => $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $riwayat->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    {{ $item->nama_gudang }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex justify-center">
                                        @if ($item->jenis == 'masuk')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Masuk
                                            </span>
                                        @elseif($item->jenis == 'keluar')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Keluar
                                            </span>
                                        @elseif($item->jenis == 'penyesuaian')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path
                                                        d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z" />
                                                </svg>
                                                Penyesuaian
                                            </span>
                                        @elseif($item->jenis == 'transfer')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path
                                                        d="M8 5a1 1 0 100 2h5.586l-1.293 1.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L13.586 5H8zM12 15a1 1 0 100-2H6.414l1.293-1.293a1 1 0 10-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L6.414 15H12z" />
                                                </svg>
                                                Transfer
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Lainnya
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-700 dark:text-gray-300">
                                    {{ number_format($item->jumlah_sebelum, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if ($item->jumlah_perubahan > 0)
                                        <span
                                            class="text-sm text-green-600 dark:text-green-400 font-medium">+{{ number_format($item->jumlah_perubahan, 0, ',', '.') }}</span>
                                    @elseif($item->jumlah_perubahan < 0)
                                        <span
                                            class="text-sm text-red-600 dark:text-red-400 font-medium">{{ number_format($item->jumlah_perubahan, 0, ',', '.') }}</span>
                                    @else
                                        <span
                                            class="text-sm text-gray-500 dark:text-gray-400">{{ number_format($item->jumlah_perubahan, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-700 dark:text-gray-300">
                                    {{ number_format($item->jumlah_setelah, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($item->referensi_tipe == 'work_order' && $item->referensi_id)
                                        <a href="{{ route('produksi.work-order.show', $item->referensi_id) }}"
                                            class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-semibold bg-indigo-100 text-indigo-800 hover:bg-indigo-200 border border-indigo-300 dark:bg-indigo-800 dark:text-indigo-100 dark:border-indigo-600 dark:hover:bg-indigo-700 transition-colors shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                                <path fill-rule="evenodd"
                                                    d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Work Order #{{ $item->nomor_referensi ?? $item->referensi_id }}
                                        </a>
                                    @elseif ($item->referensi_tipe == 'transfer_barang' && $item->referensi_id)
                                        <a href="{{ route('inventaris.transfer-gudang.show', $item->referensi_id) }}"
                                            class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-semibold bg-blue-100 text-blue-800 hover:bg-blue-200 border border-blue-300 dark:bg-blue-800 dark:text-blue-100 dark:border-blue-600 dark:hover:bg-blue-700 transition-colors shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M8 5a1 1 0 100 2h5.586l-1.293 1.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L13.586 5H8zM12 15a1 1 0 100-2H6.414l1.293-1.293a1 1 0 10-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L6.414 15H12z" />
                                            </svg>
                                            Transfer #{{ $item->nomor_referensi ?? $item->referensi_id }}
                                        </a>
                                    @elseif ($item->referensi_tipe == 'penerimaan_barang' && $item->referensi_id)
                                        <a href="{{ route('pembelian.penerimaan-barang.show', $item->referensi_id) }}"
                                            class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-semibold bg-green-100 text-green-800 hover:bg-green-200 border border-green-300 dark:bg-green-800 dark:text-green-100 dark:border-green-600 dark:hover:bg-green-700 transition-colors shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                                <path
                                                    d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" />
                                            </svg>
                                            Penerimaan #{{ $item->nomor_referensi ?? $item->referensi_id }}
                                        </a>
                                    @elseif ($item->referensi_tipe == 'delivery_order' && $item->referensi_id)
                                        <a href="{{ route('penjualan.delivery-order.show', $item->referensi_id) }}"
                                            class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-semibold bg-amber-100 text-amber-800 hover:bg-amber-200 border border-amber-300 dark:bg-amber-800 dark:text-amber-100 dark:border-amber-600 dark:hover:bg-amber-700 transition-colors shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                                <path
                                                    d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" />
                                            </svg>
                                            DO #{{ $item->nomor_referensi ?? $item->referensi_id }}
                                        </a>
                                    @elseif ($item->referensi_tipe == 'penyesuaian_stok' && $item->referensi_id)
                                        <a href="{{ route('inventaris.penyesuaian-stok.show', $item->referensi_id) }}"
                                            class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-semibold bg-purple-100 text-purple-800 hover:bg-purple-200 border border-purple-300 dark:bg-purple-800 dark:text-purple-100 dark:border-purple-600 dark:hover:bg-purple-700 transition-colors shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z" />
                                            </svg>
                                            Penyesuaian #{{ $item->nomor_referensi ?? $item->referensi_id }}
                                        </a>
                                    @elseif ($item->referensi_tipe && $item->referensi_id)
                                        <span
                                            class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-semibold bg-gray-100 text-gray-800 border border-gray-300 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
                                            {{ ucfirst(str_replace('_', ' ', $item->referensi_tipe)) }}
                                            #{{ $item->referensi_id }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                    {{ $item->keterangan ?: '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-8 text-center">
                                    <div
                                        class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-12 w-12 text-gray-300 dark:text-gray-600 mb-3" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="text-sm">Tidak ada data riwayat stok</span>
                                        <span class="text-xs mt-1">Coba ubah filter pencarian</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($riwayat->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-400">
                            Menampilkan {{ $riwayat->firstItem() ?? 0 }} sampai {{ $riwayat->lastItem() ?? 0 }} dari
                            {{ $riwayat->total() ?? 0 }} data
                        </div>
                        <div>
                            {{ $riwayat->appends(request()->query())->links('vendor.pagination.tailwind-custom') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
