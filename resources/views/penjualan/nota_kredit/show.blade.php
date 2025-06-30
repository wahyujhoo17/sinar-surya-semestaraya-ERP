<x-app-layout>
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-sm dark:bg-green-800/30 dark:border-green-600 dark:text-green-400"
                role="alert">
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-sm dark:bg-red-800/30 dark:border-red-600 dark:text-red-400"
                role="alert">
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        @endif

        {{-- Header Section --}}
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-10 w-1 rounded-full mr-3">
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Detail Nota Kredit
                            </h1>
                            <div class="mt-1 flex items-center gap-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $notaKredit->nomor }}</span>
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                    {{ $notaKredit->status === 'draft' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-300' : '' }}
                                    {{ $notaKredit->status === 'diproses' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : '' }}
                                    {{ $notaKredit->status === 'selesai' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-300' : '' }}">
                                    {{ ucfirst($notaKredit->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('penjualan.nota-kredit.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-600 hover:bg-gray-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    @if (auth()->user()->hasPermission('nota_kredit.edit') && $notaKredit->status == 'draft')
                        <a href="{{ route('penjualan.nota-kredit.edit', $notaKredit->id) }}"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Edit
                        </a>

                        @if (auth()->user()->hasPermission('nota_kredit.edit'))
                            <button type="button" data-modal-target="finalizeModal" data-modal-toggle="finalizeModal"
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Finalisasi
                            </button>
                        @endif
                    @endif
                    @if (auth()->user()->hasPermission('nota_kredit.print'))
                        <a href="{{ route('penjualan.nota-kredit.pdf', $notaKredit->id) }}" target="_blank"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Cetak PDF
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Status cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div
                class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden p-5 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-500 bg-opacity-10 text-blue-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor</h3>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $notaKredit->nomor }}</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden p-5 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-emerald-500 bg-opacity-10 text-emerald-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</h3>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $notaKredit->tanggal ? \Carbon\Carbon::parse($notaKredit->tanggal)->format('d/m/Y') : '-' }}
                        </p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden p-5 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-amber-500 bg-opacity-10 text-amber-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Customer</h3>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $notaKredit->customer->company ?? ($notaKredit->customer->nama ?? 'N/A') }}
                        </p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden p-5 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-500 bg-opacity-10 text-purple-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total</h3>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                            Rp {{ number_format($notaKredit->total, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Information --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            {{-- Nota Kredit Information --}}
            <div
                class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Nota Kredit</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-y-4">
                        <div class="grid grid-cols-3 gap-4">
                            <dt class="font-medium text-gray-600 dark:text-gray-300">Nomor Nota Kredit:</dt>
                            <dd class="col-span-2 text-gray-900 dark:text-white">{{ $notaKredit->nomor }}</dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <dt class="font-medium text-gray-600 dark:text-gray-300">Tanggal:</dt>
                            <dd class="col-span-2 text-gray-900 dark:text-white">
                                {{ $notaKredit->tanggal ? \Carbon\Carbon::parse($notaKredit->tanggal)->format('d/m/Y') : '-' }}
                            </dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <dt class="font-medium text-gray-600 dark:text-gray-300">Customer:</dt>
                            <dd class="col-span-2 text-gray-900 dark:text-white">
                                {{ $notaKredit->customer->company ?? ($notaKredit->customer->nama ?? 'N/A') }}</dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <dt class="font-medium text-gray-600 dark:text-gray-300">Status:</dt>
                            <dd class="col-span-2">
                                @if ($notaKredit->status == 'draft')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-800 dark:text-amber-100">
                                        Draft
                                    </span>
                                @elseif($notaKredit->status == 'selesai')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-800 dark:text-emerald-100">
                                        Selesai
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        {{ $notaKredit->status }}
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <dt class="font-medium text-gray-600 dark:text-gray-300">Dibuat Oleh:</dt>
                            <dd class="col-span-2 text-gray-900 dark:text-white">
                                {{ $notaKredit->user->name ?? 'N/A' }}</dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <dt class="font-medium text-gray-600 dark:text-gray-300">Catatan:</dt>
                            <dd class="col-span-2 text-gray-900 dark:text-white">
                                {{ $notaKredit->catatan ?? 'Tidak ada catatan' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Retur Information --}}
            <div
                class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Retur</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-y-4">
                        <div class="grid grid-cols-3 gap-4">
                            <dt class="font-medium text-gray-600 dark:text-gray-300">Nomor Retur:</dt>
                            <dd class="col-span-2 text-gray-900 dark:text-white">
                                @if ($notaKredit->returPenjualan)
                                    <a href="{{ route('penjualan.retur.show', $notaKredit->returPenjualan->id) }}"
                                        class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">
                                        {{ $notaKredit->returPenjualan->nomor }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <dt class="font-medium text-gray-600 dark:text-gray-300">Tanggal Retur:</dt>
                            <dd class="col-span-2 text-gray-900 dark:text-white">
                                {{ $notaKredit->returPenjualan && $notaKredit->returPenjualan->tanggal
                                    ? \Carbon\Carbon::parse($notaKredit->returPenjualan->tanggal)->format('d/m/Y')
                                    : '-' }}
                            </dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <dt class="font-medium text-gray-600 dark:text-gray-300">Sales Order:</dt>
                            <dd class="col-span-2 text-gray-900 dark:text-white">
                                @if ($notaKredit->salesOrder)
                                    <a href="{{ route('penjualan.sales-order.show', $notaKredit->salesOrder->id) }}"
                                        class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">
                                        {{ $notaKredit->salesOrder->nomor }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <dt class="font-medium text-gray-600 dark:text-gray-300">Status Retur:</dt>
                            <dd class="col-span-2">
                                @if ($notaKredit->returPenjualan)
                                    @if ($notaKredit->returPenjualan->status == 'draft')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-800 dark:text-amber-100">
                                            Draft
                                        </span>
                                    @elseif($notaKredit->returPenjualan->status == 'menunggu_persetujuan')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                            Menunggu Persetujuan
                                        </span>
                                    @elseif($notaKredit->returPenjualan->status == 'disetujui')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100">
                                            Disetujui
                                        </span>
                                    @elseif($notaKredit->returPenjualan->status == 'ditolak')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                            Ditolak
                                        </span>
                                    @elseif($notaKredit->returPenjualan->status == 'diproses')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                            Diproses
                                        </span>
                                    @elseif($notaKredit->returPenjualan->status == 'selesai')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-800 dark:text-emerald-100">
                                            Selesai
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            {{ $notaKredit->returPenjualan->status }}
                                        </span>
                                    @endif
                                @else
                                    N/A
                                @endif
                            </dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <dt class="font-medium text-gray-600 dark:text-gray-300">QC Status:</dt>
                            <dd class="col-span-2">
                                @if ($notaKredit->returPenjualan)
                                    @if ($notaKredit->returPenjualan->qc_passed === 1)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-800 dark:text-emerald-100">
                                            Lolos QC
                                        </span>
                                    @elseif($notaKredit->returPenjualan->qc_passed === 0)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                            Gagal QC
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            Belum QC
                                        </span>
                                    @endif
                                @else
                                    N/A
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Detail Items Table --}}
        <div
            class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Item</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                No.</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Kode</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Produk</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Quantity</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Satuan</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Harga</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($notaKredit->details as $index => $detail)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $detail->produk->kode ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $detail->produk->nama ?? 'Produk tidak ditemukan' }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-400">
                                    {{ number_format($detail->quantity, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $detail->satuan->nama ?? 'N/A' }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-400">
                                    Rp {{ number_format($detail->harga, 0, ',', '.') }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900 dark:text-white">
                                    Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"
                                    class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Tidak ada detail item
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th colspan="6"
                                class="px-6 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">Total:
                            </th>
                            <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">
                                Rp {{ number_format($notaKredit->total, 0, ',', '.') }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Applied Credits Section --}}
        <div class="mt-8">
            <div
                class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Aplikasi Kredit ke Invoice
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    @php
                        // $appliedToInvoices is now passed from the controller
                        // Old logic: $appliedToInvoices = $notaKredit->invoice ? [$notaKredit->invoice] : [];
                    @endphp

                    @if (isset($appliedToInvoices) && count($appliedToInvoices) > 0)
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Nomor Invoice
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Tanggal Aplikasi
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Jumlah Kredit
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Sisa Piutang
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($appliedToInvoices as $invoice)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('penjualan.invoice.show', $invoice->id) }}"
                                                class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">
                                                {{ $invoice->nomor }}
                                            </a>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($notaKredit->tanggal)->format('d/m/Y') }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-right">
                                            {{-- Display the amount from the pivot table --}}
                                            Rp {{ number_format($invoice->pivot->applied_amount, 0, ',', '.') }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-right">
                                            Rp {{ number_format($invoice->sisa_piutang, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                {{ $invoice->status_display }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th colspan="2"
                                        class="px-6 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">
                                        Total Kredit Diterapkan:
                                    </th>
                                    <th
                                        class="px-6 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">
                                        Rp
                                        {{ number_format($appliedToInvoices->sum('pivot.applied_amount'), 0, ',', '.') }}
                                    </th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
                        </table>
                        <div
                            class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-700 dark:text-gray-300">
                                    <span class="font-medium">Total Nota Kredit:</span>
                                    Rp {{ number_format($notaKredit->total, 0, ',', '.') }}
                                </div>
                                <div class="text-sm text-gray-700 dark:text-gray-300">
                                    <span class="font-medium">Total Diterapkan:</span>
                                    Rp
                                    {{ number_format($appliedToInvoices->sum('pivot.applied_amount'), 0, ',', '.') }}
                                </div>
                                <div
                                    class="text-sm font-semibold {{ $notaKredit->total - $appliedToInvoices->sum('pivot.applied_amount') > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                    <span class="font-medium">Sisa Kredit:</span>
                                    Rp
                                    {{ number_format($notaKredit->total - $appliedToInvoices->sum('pivot.applied_amount'), 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="py-10 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Kredit belum diterapkan ke invoice
                                manapun</p>

                            @if ($notaKredit->status !== 'selesai')
                                <div
                                    class="mt-6 bg-amber-50 dark:bg-amber-900/30 p-4 rounded-lg border border-amber-200 dark:border-amber-800">
                                    <h4 class="text-sm font-medium text-amber-800 dark:text-amber-300">Cara Aplikasi
                                        Kredit ke Invoice</h4>
                                    <ol
                                        class="mt-2 text-sm text-amber-700 dark:text-amber-400 list-decimal list-inside space-y-1">
                                        <li>Klik tombol "Cari Invoice untuk Aplikasi Kredit" di bawah</li>
                                        <li>Pilih invoice yang ingin diberi kredit dari daftar</li>
                                        <li>Klik tombol "Aplikasi Kredit" pada invoice yang dipilih</li>
                                        <li>Masukkan jumlah kredit yang ingin diterapkan</li>
                                        <li>Klik "Terapkan Kredit" untuk menyelesaikan proses</li>
                                    </ol>
                                </div>
                                <p class="mt-4">
                                    <a href="{{ route('penjualan.invoice.index', ['customer_id' => $notaKredit->customer_id, 'nota_kredit_id' => $notaKredit->id]) }}"
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        Cari Invoice untuk Aplikasi Kredit
                                    </a>
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Finalize Confirmation Modal --}}
    <div id="finalizeModal" tabindex="-1" aria-hidden="true"
        class="fixed inset-0 z-50 hidden w-full h-full bg-gray-900 bg-opacity-0 transition-opacity duration-300">
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="relative w-full max-w-md mx-auto transform transition-all duration-300 scale-95 opacity-0"
                id="finalizeModalContent">
                <div
                    class="relative bg-white rounded-lg shadow-xl dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                    <button type="button"
                        class="absolute top-3 right-3 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white transition-colors duration-200"
                        onclick="closeModal('finalizeModal')">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="p-8 text-center">
                        <svg class="mx-auto mb-5 text-emerald-500 w-14 h-14" aria-hidden="true" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mb-5 text-xl font-semibold text-gray-700 dark:text-gray-300">Apakah Anda yakin ingin
                            memfinalisasi nota kredit <span
                                class="font-bold text-gray-900 dark:text-white">{{ $notaKredit->nomor }}</span>?</h3>
                        <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Setelah difinalisasi, nota kredit
                            tidak
                            dapat diubah lagi.</p>
                        <form action="{{ route('penjualan.nota-kredit.finalize', $notaKredit->id) }}" method="POST">
                            @csrf
                            <div class="flex justify-center space-x-4">
                                <button type="submit"
                                    class="text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:outline-none focus:ring-emerald-300 dark:focus:ring-emerald-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center transition-colors duration-200 shadow-sm hover:shadow-md">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Finalisasi
                                </button>
                                <button type="button"
                                    class="text-gray-700 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-300 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-700 transition-colors duration-200 shadow-sm hover:shadow-md"
                                    onclick="closeModal('finalizeModal')">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Complete Confirmation Modal --}}
    <div id="completeModal" tabindex="-1" aria-hidden="true"
        class="fixed inset-0 z-50 hidden w-full h-full bg-gray-900 bg-opacity-0 transition-opacity duration-300">
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="relative w-full max-w-md mx-auto transform transition-all duration-300 scale-95 opacity-0"
                id="completeModalContent">
                <div
                    class="relative bg-white rounded-lg shadow-xl dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                    <button type="button"
                        class="absolute top-3 right-3 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white transition-colors duration-200"
                        onclick="closeModal('completeModal')">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="p-8 text-center">
                        <svg class="mx-auto mb-5 text-emerald-500 w-14 h-14" aria-hidden="true" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mb-5 text-xl font-semibold text-gray-700 dark:text-gray-300">Apakah Anda yakin ingin
                            menyelesaikan nota kredit <span
                                class="font-bold text-gray-900 dark:text-white">{{ $notaKredit->nomor }}</span>?</h3>
                        <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Setelah diselesaikan, status nota
                            kredit akan menjadi 'selesai' dan tidak dapat diubah lagi.</p>
                        <form action="{{ route('penjualan.nota-kredit.complete', $notaKredit->id) }}" method="POST">
                            @csrf
                            <div class="flex justify-center space-x-4">
                                <button type="submit"
                                    class="text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:outline-none focus:ring-emerald-300 dark:focus:ring-emerald-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center transition-colors duration-200 shadow-sm hover:shadow-md">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Selesaikan
                                </button>
                                <button type="button"
                                    class="text-gray-700 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-300 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-700 transition-colors duration-200 shadow-sm hover:shadow-md"
                                    onclick="closeModal('completeModal')">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            const modalContent = document.getElementById(modalId + 'Content');

            if (modal) {
                // Show the modal
                modal.classList.remove('hidden');
                setTimeout(() => {
                    // Fade in background
                    modal.classList.remove('bg-opacity-0');
                    modal.classList.add('bg-opacity-50');

                    // Show and animate content
                    if (modalContent) {
                        modalContent.classList.remove('scale-95', 'opacity-0');
                        modalContent.classList.add('scale-100', 'opacity-100');
                    }
                }, 10); // Small delay to ensure the transition works

                // Add flex display for centering
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden'; // Prevent scrolling
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            const modalContent = document.getElementById(modalId + 'Content');

            if (modal) {
                // Fade out background
                modal.classList.remove('bg-opacity-50');
                modal.classList.add('bg-opacity-0');

                // Hide and animate content
                if (modalContent) {
                    modalContent.classList.remove('scale-100', 'opacity-100');
                    modalContent.classList.add('scale-95', 'opacity-0');
                }

                // Hide the modal after transition completes
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    document.body.style.overflow = ''; // Restore scrolling
                }, 300);
            }
        }

        // Initialize modal triggers
        document.addEventListener('DOMContentLoaded', function() {
            const modalTriggers = document.querySelectorAll('[data-modal-toggle]');
            modalTriggers.forEach(trigger => {
                const modalId = trigger.getAttribute('data-modal-target');
                trigger.addEventListener('click', () => openModal(modalId));
            });
        });
    </script>
</x-app-layout>
