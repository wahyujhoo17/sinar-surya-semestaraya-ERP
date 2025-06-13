<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div
            class="mb-6 bg-gradient-to-r from-white to-gray-50 dark:from-gray-800 dark:to-gray-800/70 rounded-xl shadow p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <div class="flex-shrink-0 bg-gradient-to-br from-blue-500 to-indigo-600 h-9 w-1.5 rounded-full">
                        </div>
                        Detail Pembelian
                    </h1>
                    <p class="mt-2 text-sm md:text-base text-gray-600 dark:text-gray-400 max-w-3xl">
                        Detail transaksi pembelian untuk faktur <span
                            class="font-semibold">{{ $pembelian->nomor_faktur }}</span>
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('laporan.pembelian.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition-all duration-200 shadow-sm focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                clip-rule="evenodd" />
                        </svg>
                        Kembali
                    </a>
                    <a href="{{ route('laporan.pembelian.export.pdf', ['id' => $pembelian->id]) }}" target="_blank"
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"
                                clip-rule="evenodd" />
                        </svg>
                        Unduh PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Info Cards -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card 1: Tanggal Pembelian -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Pembelian</p>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-1">
                            {{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d M Y') }}
                        </h3>
                    </div>
                    <div
                        class="rounded-full p-3 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 2: Supplier -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Supplier</p>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-1">
                            {{ $pembelian->supplier->nama }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $pembelian->supplier->kode }}
                        </p>
                    </div>
                    <div
                        class="rounded-full p-3 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 3: Total Pembelian -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pembelian</p>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-1">
                            Rp {{ number_format($pembelian->total, 0, ',', '.') }}
                        </h3>
                    </div>
                    <div class="rounded-full p-3 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 4: Status Pembayaran -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pembayaran</p>
                        <div class="mt-1">
                            @if ($pembelian->status == 'lunas')
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    Lunas
                                </span>
                            @elseif($pembelian->status == 'sebagian')
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                    Dibayar Sebagian
                                </span>
                                <p class="text-base font-semibold text-gray-700 dark:text-gray-300 mt-1">
                                    Rp {{ number_format($pembelian->total_bayar, 0, ',', '.') }}
                                </p>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                    Belum Bayar
                                </span>
                            @endif
                        </div>
                    </div>
                    <div
                        class="rounded-full p-3 bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Items Table -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/60">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    Detail Item Pembelian
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Daftar item yang dibeli pada transaksi ini
                </p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                No
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Kode Produk
                            </th>
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
                                Satuan
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
                        @forelse($pembelian->details ?? [] as $index => $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $item->produk->kode }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $item->produk->nama }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $item->quantity }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $item->produk->satuan->nama }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $item->diskon_persen ?? 0 }}%
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                    Tidak ada data item pembelian
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <td colspan="7"
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white text-right">
                                Total:
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                                Rp {{ number_format($pembelian->total, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Keterangan -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    Keterangan
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $pembelian->keterangan ?? 'Tidak ada keterangan' }}
                </p>
            </div>

            <!-- Informasi Tambahan -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    Informasi Tambahan
                </h3>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat oleh:</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $pembelian->user->name ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal dibuat:</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($pembelian->created_at)->format('d M Y H:i') }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir diupdate:</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($pembelian->updated_at)->format('d M Y H:i') }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-app-layout>
