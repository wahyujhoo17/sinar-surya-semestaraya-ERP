<x-app-layout>
    @php
        // Helper function to get status color
        function poStatusColor($status)
        {
            switch ($status) {
                case 'draft':
                    return 'gray';
                case 'diproses':
                    return 'blue';
                case 'dikirim':
                    return 'amber';
                case 'selesai':
                    return 'emerald';
                case 'dibatalkan':
                    return 'red';
                default:
                    return 'primary';
            }
        }

        // Get the badge color and icon for the current status
        $statusBgColor =
            'bg-' .
            poStatusColor($purchaseOrder->status) .
            '-100 dark:bg-' .
            poStatusColor($purchaseOrder->status) .
            '-900/30';
        $statusTextColor =
            'text-' .
            poStatusColor($purchaseOrder->status) .
            '-800 dark:text-' .
            poStatusColor($purchaseOrder->status) .
            '-300';
        $statusIconColor =
            'text-' .
            poStatusColor($purchaseOrder->status) .
            '-500 dark:text-' .
            poStatusColor($purchaseOrder->status) .
            '-400';
    @endphp

    <div class="max-w-full mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                                </path>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('pembelian.purchasing-order.index') }}"
                                class="ml-1 text-gray-500 hover:text-gray-700 md:ml-2 dark:text-gray-400 dark:hover:text-white">
                                Purchase Order
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span
                                class="ml-1 font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{ $purchaseOrder->nomor }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header with Status and Action Buttons -->
        <div class="md:flex md:items-center md:justify-between border-b border-gray-200 dark:border-gray-700 pb-5 mb-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $purchaseOrder->nomor }}
                    </h1>
                    <div class="flex items-center px-3 py-1 rounded-full {{ $statusBgColor }} {{ $statusTextColor }}">
                        <span
                            class="flex w-2 h-2 mr-1.5 rounded-full bg-{{ poStatusColor($purchaseOrder->status) }}-500"></span>
                        <span class="text-sm font-medium capitalize">{{ $purchaseOrder->status }}</span>
                    </div>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Dibuat oleh {{ $purchaseOrder->user->name }} pada
                    {{ \Carbon\Carbon::parse($purchaseOrder->created_at)->format('d M Y, H:i') }}
                </p>
            </div>

            <div class="flex flex-wrap gap-2 mt-4 md:mt-0">
                @if ($purchaseOrder->status == 'draft')
                    <a href="{{ route('pembelian.purchasing-order.edit', $purchaseOrder->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md text-sm font-medium text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Edit
                    </a>
                @endif

                <form action="{{ route('pembelian.purchasing-order.change-status', $purchaseOrder->id) }}"
                    method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <div class="inline-flex relative">
                        <select name="status" onchange="this.form.submit()"
                            class="pl-4 pr-8 py-2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
                            <option value="draft" {{ $purchaseOrder->status == 'draft' ? 'selected' : '' }}>Draft
                            </option>
                            <option value="diproses" {{ $purchaseOrder->status == 'diproses' ? 'selected' : '' }}>
                                Diproses</option>
                            <option value="dikirim" {{ $purchaseOrder->status == 'dikirim' ? 'selected' : '' }}>Dikirim
                            </option>
                            <option value="selesai" {{ $purchaseOrder->status == 'selesai' ? 'selected' : '' }}>Selesai
                            </option>
                            <option value="dibatalkan" {{ $purchaseOrder->status == 'dibatalkan' ? 'selected' : '' }}>
                                Dibatalkan</option>
                        </select>
                        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                    </div>
                </form>

                <a href="#" onclick="window.print()"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Left Column - PO Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- General Information -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Purchase Order</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Info -->
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor PO</h3>
                                <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $purchaseOrder->nomor }}</p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($purchaseOrder->tanggal)->format('d M Y') }}</p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Supplier</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $purchaseOrder->supplier->nama ?? '-' }}</p>
                                @if ($purchaseOrder->supplier && ($purchaseOrder->supplier->telepon || $purchaseOrder->supplier->email))
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $purchaseOrder->supplier->telepon ?? '' }}
                                        {{ $purchaseOrder->supplier->telepon && $purchaseOrder->supplier->email ? ' | ' : '' }}
                                        {{ $purchaseOrder->supplier->email ?? '' }}
                                    </p>
                                @endif
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Permintaan Pembelian
                                </h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    @if ($purchaseOrder->purchaseRequest)
                                        <a href="{{ route('pembelian.permintaan-pembelian.show', $purchaseOrder->purchaseRequest->id) }}"
                                            class="text-primary-600 hover:text-primary-700 hover:underline">
                                            {{ $purchaseOrder->purchaseRequest->nomor }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Delivery Info -->
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Pengiriman</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $purchaseOrder->tanggal_pengiriman ? \Carbon\Carbon::parse($purchaseOrder->tanggal_pengiriman)->format('d M Y') : '-' }}
                                </p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat Pengiriman</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $purchaseOrder->alamat_pengiriman ?? '-' }}
                                </p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pembayaran</h3>
                                <div class="mt-1 flex items-center">
                                    @php
                                        $paymentStatusColor = match ($purchaseOrder->status_pembayaran) {
                                            'belum_bayar'
                                                => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400',
                                            'sebagian'
                                                => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
                                            'lunas'
                                                => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-400',
                                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400',
                                        };
                                    @endphp
                                    <span
                                        class="px-2.5 py-0.5 text-xs font-medium rounded-full {{ $paymentStatusColor }}">
                                        @if ($purchaseOrder->status_pembayaran == 'belum_bayar')
                                            Belum Bayar
                                        @elseif($purchaseOrder->status_pembayaran == 'sebagian')
                                            Dibayar Sebagian
                                        @elseif($purchaseOrder->status_pembayaran == 'lunas')
                                            Lunas
                                        @else
                                            {{ $purchaseOrder->status_pembayaran }}
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Penerimaan</h3>
                                <div class="mt-1 flex items-center">
                                    @php
                                        $receiptStatusColor = match ($purchaseOrder->status_penerimaan) {
                                            'belum_diterima'
                                                => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400',
                                            'sebagian'
                                                => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
                                            'diterima'
                                                => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-400',
                                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400',
                                        };
                                    @endphp
                                    <span
                                        class="px-2.5 py-0.5 text-xs font-medium rounded-full {{ $receiptStatusColor }}">
                                        @if ($purchaseOrder->status_penerimaan == 'belum_diterima')
                                            Belum Diterima
                                        @elseif($purchaseOrder->status_penerimaan == 'sebagian')
                                            Diterima Sebagian
                                        @elseif($purchaseOrder->status_penerimaan == 'diterima')
                                            Diterima Penuh
                                        @else
                                            {{ $purchaseOrder->status_penerimaan }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan</h3>
                        <div
                            class="mt-2 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg text-sm text-gray-700 dark:text-gray-300">
                            {{ $purchaseOrder->catatan ?? 'Tidak ada catatan' }}
                        </div>
                    </div>
                </div>

                <!-- PO Details/Items Table -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Item</h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        No
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Item
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Deskripsi
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Qty
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
                                @forelse ($purchaseOrder->details as $index => $detail)
                                    <tr>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                            <div class="font-medium">{{ $detail->produk->kode ?? '-' }}</div>
                                            <div>{{ $detail->produk->nama ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $detail->deskripsi ?? '-' }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $detail->quantity }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $detail->satuan->nama ?? '-' }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ number_format($detail->harga, 0, ',', '.') }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $detail->diskon > 0 ? number_format($detail->diskon, 0, ',', '.') : '-' }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8"
                                            class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-gray-400">
                                            Tidak ada item
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary -->
                    <div
                        class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col items-end gap-2">
                            <div class="grid grid-cols-2 gap-x-8 text-right w-full max-w-xs">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Subtotal:</span>
                                <span
                                    class="text-sm text-gray-900 dark:text-white font-medium">{{ number_format($purchaseOrder->subtotal, 0, ',', '.') }}</span>

                                @if ($purchaseOrder->diskon_persen > 0 || $purchaseOrder->diskon_nominal > 0)
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Diskon:</span>
                                    <span class="text-sm text-red-600 dark:text-red-400 font-medium">
                                        {{ $purchaseOrder->diskon_persen > 0 ? $purchaseOrder->diskon_persen . '%' : '' }}
                                        {{ $purchaseOrder->diskon_persen > 0 && $purchaseOrder->diskon_nominal > 0 ? ' + ' : '' }}
                                        {{ $purchaseOrder->diskon_nominal > 0 ? number_format($purchaseOrder->diskon_nominal, 0, ',', '.') : '' }}
                                    </span>
                                @endif

                                @if ($purchaseOrder->ppn > 0)
                                    <span class="text-sm text-gray-500 dark:text-gray-400">PPN
                                        ({{ $purchaseOrder->ppn }}%):</span>
                                    <span class="text-sm text-gray-900 dark:text-white font-medium">
                                        {{ number_format($purchaseOrder->subtotal * ($purchaseOrder->ppn / 100), 0, ',', '.') }}
                                    </span>
                                @endif

                                <span class="text-base text-gray-800 dark:text-gray-200 font-semibold">Total:</span>
                                <span
                                    class="text-base text-gray-900 dark:text-white font-bold">{{ number_format($purchaseOrder->total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Status, Actions and Other Info -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status & Pembayaran</h2>

                    <div class="space-y-4">
                        <!-- Status Timeline -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Progress PO</h3>
                            <div class="mt-2 flex justify-between items-center">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="{{ $purchaseOrder->status == 'draft' ? 'bg-blue-500' : 'bg-gray-300 dark:bg-gray-600' }} h-3 w-3 rounded-full">
                                    </div>
                                    <span class="text-xs mt-1 text-center">Draft</span>
                                </div>
                                <div class="h-0.5 flex-1 bg-gray-200 dark:bg-gray-700 mx-2"></div>
                                <div class="flex flex-col items-center">
                                    <div
                                        class="{{ in_array($purchaseOrder->status, ['diproses', 'dikirim', 'selesai']) ? 'bg-blue-500' : 'bg-gray-300 dark:bg-gray-600' }} h-3 w-3 rounded-full">
                                    </div>
                                    <span class="text-xs mt-1 text-center">Diproses</span>
                                </div>
                                <div class="h-0.5 flex-1 bg-gray-200 dark:bg-gray-700 mx-2"></div>
                                <div class="flex flex-col items-center">
                                    <div
                                        class="{{ in_array($purchaseOrder->status, ['dikirim', 'selesai']) ? 'bg-blue-500' : 'bg-gray-300 dark:bg-gray-600' }} h-3 w-3 rounded-full">
                                    </div>
                                    <span class="text-xs mt-1 text-center">Dikirim</span>
                                </div>
                                <div class="h-0.5 flex-1 bg-gray-200 dark:bg-gray-700 mx-2"></div>
                                <div class="flex flex-col items-center">
                                    <div
                                        class="{{ $purchaseOrder->status == 'selesai' ? 'bg-blue-500' : 'bg-gray-300 dark:bg-gray-600' }} h-3 w-3 rounded-full">
                                    </div>
                                    <span class="text-xs mt-1 text-center">Selesai</span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Status -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pembayaran</h3>
                            <div class="mt-2">
                                @php
                                    $paymentStatusColor = match ($purchaseOrder->status_pembayaran) {
                                        'belum_bayar' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400',
                                        'sebagian'
                                            => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
                                        'lunas'
                                            => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-400',
                                        default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400',
                                    };

                                    $paymentStatusWidth = match ($purchaseOrder->status_pembayaran) {
                                        'belum_bayar' => 'w-0',
                                        'sebagian' => 'w-1/2',
                                        'lunas' => 'w-full',
                                        default => 'w-0',
                                    };

                                    $paymentStatusBarColor = match ($purchaseOrder->status_pembayaran) {
                                        'belum_bayar' => 'bg-red-500 dark:bg-red-600',
                                        'sebagian' => 'bg-yellow-500 dark:bg-yellow-600',
                                        'lunas' => 'bg-emerald-500 dark:bg-emerald-600',
                                        default => 'bg-gray-500 dark:bg-gray-600',
                                    };
                                @endphp
                                <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                    <div
                                        class="{{ $paymentStatusBarColor }} h-full {{ $paymentStatusWidth }} rounded-full">
                                    </div>
                                </div>
                                <div class="flex justify-between mt-1">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">0%</span>
                                    <span class="text-xs font-medium {{ $paymentStatusColor }} px-1 rounded">
                                        @if ($purchaseOrder->status_pembayaran == 'belum_bayar')
                                            Belum Bayar
                                        @elseif($purchaseOrder->status_pembayaran == 'sebagian')
                                            Dibayar Sebagian
                                        @elseif($purchaseOrder->status_pembayaran == 'lunas')
                                            Lunas
                                        @else
                                            {{ $purchaseOrder->status_pembayaran }}
                                        @endif
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">100%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Receipt Status -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Penerimaan</h3>
                            <div class="mt-2">
                                @php
                                    $receiptStatusColor = match ($purchaseOrder->status_penerimaan) {
                                        'belum_diterima'
                                            => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400',
                                        'sebagian'
                                            => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
                                        'diterima'
                                            => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-400',
                                        default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400',
                                    };

                                    $receiptStatusWidth = match ($purchaseOrder->status_penerimaan) {
                                        'belum_diterima' => 'w-0',
                                        'sebagian' => 'w-1/2',
                                        'diterima' => 'w-full',
                                        default => 'w-0',
                                    };

                                    $receiptStatusBarColor = match ($purchaseOrder->status_penerimaan) {
                                        'belum_diterima' => 'bg-red-500 dark:bg-red-600',
                                        'sebagian' => 'bg-yellow-500 dark:bg-yellow-600',
                                        'diterima' => 'bg-emerald-500 dark:bg-emerald-600',
                                        default => 'bg-gray-500 dark:bg-gray-600',
                                    };
                                @endphp
                                <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                    <div
                                        class="{{ $receiptStatusBarColor }} h-full {{ $receiptStatusWidth }} rounded-full">
                                    </div>
                                </div>
                                <div class="flex justify-between mt-1">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">0%</span>
                                    <span class="text-xs font-medium {{ $receiptStatusColor }} px-1 rounded">
                                        @if ($purchaseOrder->status_penerimaan == 'belum_diterima')
                                            Belum Diterima
                                        @elseif($purchaseOrder->status_penerimaan == 'sebagian')
                                            Diterima Sebagian
                                        @elseif($purchaseOrder->status_penerimaan == 'diterima')
                                            Diterima Penuh
                                        @else
                                            {{ $purchaseOrder->status_penerimaan }}
                                        @endif
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">100%</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2 flex flex-col gap-2">
                            <a href="#"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Catat Penerimaan Barang
                            </a>

                            <a href="#"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                </svg>
                                Catat Pembayaran
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Terms & Conditions -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Syarat & Ketentuan</h2>
                    <div class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                        {!! nl2br(e($purchaseOrder->syarat_ketentuan ?? 'Tidak ada syarat dan ketentuan yang ditentukan.')) !!}
                    </div>
                </div>

                <!-- Activity Log -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aktivitas</h2>
                    <div class="space-y-4">
                        <div class="relative flex items-start">
                            <div>
                                <div class="relative px-1">
                                    <div
                                        class="h-8 w-8 bg-primary-100 dark:bg-primary-900/20 rounded-full ring-8 ring-white dark:ring-gray-800 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 text-primary-600 dark:text-primary-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1 py-0.5">
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($purchaseOrder->created_at)->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1 ml-4 py-1.5">
                                <div>
                                    <p class="text-sm text-gray-900 dark:text-white font-medium">PO Dibuat</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $purchaseOrder->user->name ?? 'Unknown' }} membuat purchase order
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if ($purchaseOrder->created_at != $purchaseOrder->updated_at)
                            <div class="relative flex items-start">
                                <div>
                                    <div class="relative px-1">
                                        <div
                                            class="h-8 w-8 bg-blue-100 dark:bg-blue-900/20 rounded-full ring-8 ring-white dark:ring-gray-800 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1 py-0.5">
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($purchaseOrder->updated_at)->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1 ml-4 py-1.5">
                                    <div>
                                        <p class="text-sm text-gray-900 dark:text-white font-medium">PO Diperbarui</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            Purchase order diperbarui
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Use Additional Activity Logs Here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function poStatusColor(status) {
                switch (status) {
                    case 'draft':
                        return 'gray';
                    case 'diproses':
                        return 'blue';
                    case 'dikirim':
                        return 'amber';
                    case 'selesai':
                        return 'emerald';
                    case 'dibatalkan':
                        return 'red';
                    default:
                        return 'primary';
                }
            }
        </script>
    @endpush
</x-app-layout>
