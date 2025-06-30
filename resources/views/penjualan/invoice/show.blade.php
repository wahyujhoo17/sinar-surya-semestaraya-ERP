<x-app-layout>
    @php
        // Helper function to get status color
        function invoiceStatusColor($status)
        {
            switch ($status) {
                case 'belum_bayar':
                    return 'red';
                case 'sebagian':
                    return 'amber';
                case 'lunas':
                    return 'emerald';
                default:
                    return 'gray';
            }
        }

        // Get the badge color for the current status
        $statusBgColor =
            'bg-' .
            invoiceStatusColor($invoice->status) .
            '-100 dark:bg-' .
            invoiceStatusColor($invoice->status) .
            '-900/30';
        $statusTextColor =
            'text-' .
            invoiceStatusColor($invoice->status) .
            '-800 dark:text-' .
            invoiceStatusColor($invoice->status) .
            '-300';
    @endphp

    @push('styles')
        <style>
            .card {
                transition: all 0.2s ease;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            }

            .card:hover {
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.12);
            }

            .table-wrapper {
                border-radius: 0.5rem;
                overflow: hidden;
            }
        </style>
    @endpush

    <div class="max-w-full mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb Navigation -->
        <div class="mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                                </path>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('penjualan.invoice.index') }}"
                                class="ml-1 text-gray-500 hover:text-gray-700 md:ml-2 dark:text-gray-400 dark:hover:text-white">
                                Invoice
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span
                                class="ml-1 font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{ $invoice->nomor }}</span>
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
                        Invoice #{{ $invoice->nomor }}
                    </h1>
                    <div class="flex items-center px-3 py-1 rounded-full {{ $statusBgColor }} {{ $statusTextColor }}">
                        <span
                            class="flex w-2 h-2 mr-1.5 rounded-full bg-{{ invoiceStatusColor($invoice->status) }}-500"></span>
                        <span class="text-sm font-medium">
                            @if ($invoice->status === 'belum_bayar')
                                Belum Bayar
                            @elseif($invoice->status === 'sebagian')
                                Bayar Sebagian
                            @elseif($invoice->status === 'lunas')
                                Lunas
                            @else
                                {{ ucfirst($invoice->status) }}
                            @endif
                        </span>
                    </div>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Dibuat oleh {{ $invoice->user->name }} pada
                    {{ \Carbon\Carbon::parse($invoice->created_at)->format('d M Y, H:i') }}
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-2 mt-4 md:mt-0">
                <a href="{{ route('penjualan.invoice.print', $invoice->id) }}" target="_blank"
                    class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium">
                    <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak
                </a>

                @if (auth()->user()->hasPermission('invoice.edit') && $invoice->status !== 'lunas')
                    <a href="{{ route('penjualan.invoice.edit', $invoice->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm font-medium">
                        <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Edit
                    </a>

                    @if (auth()->user()->hasPermission('invoice.delete'))
                        <form action="{{ route('penjualan.invoice.destroy', $invoice->id) }}" method="POST"
                            onsubmit="return confirm('Anda yakin ingin menghapus invoice ini?');" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium">
                                <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>

        <!-- Invoice Information Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Invoice Details -->
            <div
                class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6 card">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Invoice</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Nomor</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $invoice->nomor }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Tanggal</span>
                        <span
                            class="font-medium text-gray-900 dark:text-white">{{ date('d/m/Y', strtotime($invoice->tanggal)) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Jatuh Tempo</span>
                        <span
                            class="font-medium text-red-600 dark:text-red-400">{{ date('d/m/Y', strtotime($invoice->jatuh_tempo)) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Dibuat Oleh</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $invoice->user->name }}</span>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div
                class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6 card">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Customer</h3>
                </div>
                <div class="space-y-3">
                    @if ($invoice->customer->company ?? $invoice->customer->nama)
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Perusahaan</span>
                            <span
                                class="font-medium text-gray-900 dark:text-white">{{ $invoice->customer->company ?? $invoice->customer->nama }}</span>
                        </div>
                    @endif
                    @if ($invoice->customer->email)
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Email</span>
                            <span
                                class="font-medium text-gray-900 dark:text-white">{{ $invoice->customer->email }}</span>
                        </div>
                    @endif
                    @if ($invoice->customer->telepon)
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Telepon</span>
                            <span
                                class="font-medium text-gray-900 dark:text-white">{{ $invoice->customer->telepon }}</span>
                        </div>
                    @endif
                    @if ($invoice->customer->kontak_person)
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Kontak</span>
                            <span
                                class="font-medium text-gray-900 dark:text-white">{{ $invoice->customer->kontak_person }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sales Order Information -->
            <div
                class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6 card">
                <div class="flex items-center gap-3 mb-4">
                    <div
                        class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sales Order</h3>
                </div>
                @if ($invoice->salesOrder)
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Nomor SO</span>
                            <a href="{{ route('penjualan.sales-order.show', $invoice->salesOrder->id) }}"
                                target="_blank"
                                class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium hover:underline">
                                {{ $invoice->salesOrder->nomor }}
                            </a>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Tanggal SO</span>
                            <span
                                class="font-medium text-gray-900 dark:text-white">{{ date('d/m/Y', strtotime($invoice->salesOrder->tanggal)) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Total SO</span>
                            <span class="font-medium text-green-600 dark:text-green-400">Rp
                                {{ number_format($invoice->salesOrder->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-gray-500 dark:text-gray-400">Tidak ada data Sales Order</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Invoice Items -->
        <div
            class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 mb-6 card">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Item</h3>
                </div>
            </div>

            <div class="table-wrapper">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    No</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Produk</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Deskripsi</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Qty</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Satuan</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Harga</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Diskon</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @if ($invoice->details->isEmpty())
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                            <p class="text-gray-500 dark:text-gray-400">Tidak ada data item</p>
                                        </div>
                                    </td>
                                </tr>
                            @else
                                @php $no = 1; @endphp
                                @foreach ($invoice->details as $detail)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $no++ }}</td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $detail->produk->nama ?? 'Produk tidak ditemukan' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $detail->deskripsi ?? '-' }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900 dark:text-white">
                                            {{ number_format($detail->quantity, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $detail->satuan->nama }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900 dark:text-white">
                                            Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900 dark:text-white">
                                            {{ $detail->diskon > 0 ? 'Rp ' . number_format($detail->diskon, 0, ',', '.') : '-' }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-green-600 dark:text-green-400">
                                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Summary and Notes -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Notes -->
            <div
                class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6 card">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Catatan</h3>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border-l-4 border-blue-500 min-h-[100px]">
                    <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">
                        {{ $invoice->catatan ?? 'Tidak ada catatan' }}
                    </p>
                </div>
            </div>

            <!-- Financial Summary -->
            <div
                class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6 card">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ringkasan Biaya</h3>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                        <span class="font-medium text-gray-900 dark:text-white">Rp
                            {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                    </div>

                    @if ($invoice->diskon_persen > 0 || $invoice->diskon_nominal > 0)
                        <div class="flex justify-between">
                            <span class="text-red-600 dark:text-red-400">
                                Diskon
                                @if ($invoice->diskon_persen > 0)
                                    <span
                                        class="ml-1 px-2 py-0.5 text-xs bg-red-200 dark:bg-red-800 rounded-full">{{ number_format($invoice->diskon_persen, 1, ',', '.') }}%</span>
                                @endif
                            </span>
                            <span class="font-medium text-red-600 dark:text-red-400">- Rp
                                {{ number_format($invoice->diskon_nominal, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Ongkos Kirim</span>
                        <span class="font-medium text-gray-900 dark:text-white">Rp
                            {{ number_format($invoice->ongkos_kirim ?? 0, 0, ',', '.') }}</span>
                    </div>

                    @if ($invoice->ppn > 0)
                        <div class="flex justify-between">
                            <span class="text-blue-600 dark:text-blue-400">
                                PPN <span
                                    class="ml-1 px-2 py-0.5 text-xs bg-blue-200 dark:bg-blue-800 rounded-full">{{ setting('tax_percentage', 11) }}%</span>
                            </span>
                            <span class="font-medium text-blue-600 dark:text-blue-400">Rp
                                {{ number_format($invoice->ppn, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div class="border-t border-gray-200 dark:border-gray-600 pt-3 mt-3">
                        <div class="flex justify-between">
                            <span class="text-lg font-bold text-gray-900 dark:text-white">Total</span>
                            <span class="text-lg font-bold text-green-600 dark:text-green-400">Rp
                                {{ number_format($invoice->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment History -->
        @if (count($invoice->pembayaranPiutang) > 0)
            <div
                class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 card">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Riwayat Pembayaran</h3>
                    </div>
                </div>

                <div class="table-wrapper">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        No</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Tanggal</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Metode</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Referensi</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Catatan</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @php
                                    $no = 1;
                                    $totalPembayaran = 0;
                                @endphp
                                @foreach ($invoice->pembayaranPiutang as $pembayaran)
                                    @php $totalPembayaran += $pembayaran->jumlah; @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $no++ }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ date('d/m/Y', strtotime($pembayaran->tanggal)) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $pembayaran->metode_pembayaran }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $pembayaran->no_referensi ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                            {{ $pembayaran->catatan ?? '-' }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-green-600 dark:text-green-400">
                                            Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach

                                <!-- Summary Rows -->
                                <tr class="bg-gray-100 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                                    <td colspan="5"
                                        class="px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-white">
                                        Total Pembayaran:</td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-green-600 dark:text-green-400">
                                        Rp {{ number_format($totalPembayaran, 0, ',', '.') }}
                                    </td>
                                </tr>

                                @if ($invoice->kredit_terapkan > 0)
                                    <tr
                                        class="bg-purple-50 dark:bg-purple-900/20 border-t border-purple-200 dark:border-purple-700">
                                        <td colspan="5"
                                            class="px-6 py-4 text-right text-sm font-medium text-purple-800 dark:text-purple-200">
                                            Kredit Terapkan:</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-purple-600 dark:text-purple-400">
                                            Rp {{ number_format($invoice->kredit_terapkan, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endif

                                <tr
                                    class="bg-green-50 dark:bg-green-900/20 border-t-2 border-green-200 dark:border-green-700">
                                    <td colspan="5"
                                        class="px-6 py-4 text-right text-sm font-medium text-green-800 dark:text-green-200">
                                        Total Pembayaran + Kredit:</td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-green-600 dark:text-green-400">
                                        @php $totalSeluruhPembayaran = $totalPembayaran + ($invoice->kredit_terapkan ?? 0); @endphp
                                        Rp {{ number_format($totalSeluruhPembayaran, 0, ',', '.') }}
                                    </td>
                                </tr>

                                <tr class="bg-gray-100 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                                    <td colspan="5"
                                        class="px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-white">
                                        Sisa Pembayaran:</td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-right text-base font-bold {{ $invoice->sisa_piutang > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                        Rp {{ number_format($invoice->sisa_piutang, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

</x-app-layout>
