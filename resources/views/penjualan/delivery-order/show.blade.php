<x-app-layout>
    @php
        // Helper function to get status color
        function statusColor($status)
        {
            switch ($status) {
                case 'draft':
                    return 'amber';
                case 'dikirim':
                    return 'blue';
                case 'selesai':
                    return 'emerald';
                case 'batal':
                    return 'red';
                default:
                    return 'gray';
            }
        }

        // Helper function to get status label
        function statusLabel($status)
        {
            $statuses = [
                'draft' => 'Draft',
                'dikirim' => 'Dikirim',
                'selesai' => 'Selesai',
                'batal' => 'Batal',
            ];

            return $statuses[$status] ?? ucfirst($status);
        }

        // Get status styling
        $statusBgColor =
            'bg-' .
            statusColor($deliveryOrder->status) .
            '-100 dark:bg-' .
            statusColor($deliveryOrder->status) .
            '-900/30';
        $statusTextColor =
            'text-' .
            statusColor($deliveryOrder->status) .
            '-800 dark:text-' .
            statusColor($deliveryOrder->status) .
            '-300';
        $statusIconColor =
            'text-' .
            statusColor($deliveryOrder->status) .
            '-500 dark:text-' .
            statusColor($deliveryOrder->status) .
            '-400';
    @endphp

    @push('styles')
        <style>
            [x-cloak] {
                display: none !important;
            }

            .status-transition {
                transition: all 0.3s ease;
            }

            .status-dropdown-item:hover {
                transform: translateX(3px);
            }

            .status-history-line {
                position: absolute;
                left: 15px;
                top: 24px;
                bottom: 0;
                width: 2px;
                background-color: #e5e7eb;
                z-index: 0;
            }

            .dark .status-history-line {
                background-color: #374151;
            }

            .table-wrapper {
                border-radius: 0.5rem;
                overflow: hidden;
            }

            /* Mobile responsiveness */
            @media (max-width: 640px) {
                .action-buttons {
                    display: flex;
                    flex-direction: column;
                    gap: 0.5rem;
                    width: 100%;
                }

                .action-buttons button,
                .action-buttons a {
                    width: 100%;
                    justify-content: center;
                }
            }

            /* Form focus styles */
            .form-input:focus-within {
                box-shadow: 0 0 0 2px rgba(var(--primary-color-rgb), 0.25);
            }
        </style>
    @endpush

    <div class="max-w-full mx-auto py-6 px-4 sm:px-6 lg:px-8" x-data="deliveryOrderActions"
        data-current-status="{{ $deliveryOrder->status }}">
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
                            <a href="{{ route('penjualan.delivery-order.index') }}"
                                class="ml-1 text-gray-500 hover:text-gray-700 md:ml-2 dark:text-gray-400 dark:hover:text-white">
                                Delivery Order
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
                                class="ml-1 font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{ $deliveryOrder->nomor }}</span>
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
                        {{ $deliveryOrder->nomor }}
                    </h1>
                    <div
                        class="flex items-center px-3 py-1 rounded-full {{ $statusBgColor }} {{ $statusTextColor }} status-transition">
                        <span
                            class="flex w-2 h-2 mr-1.5 rounded-full bg-{{ statusColor($deliveryOrder->status) }}-500"></span>
                        <span class="text-sm font-medium capitalize">{{ statusLabel($deliveryOrder->status) }}</span>
                    </div>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Dibuat oleh {{ $deliveryOrder->createdBy->name ?? 'User tidak ditemukan' }} pada
                    {{ \Carbon\Carbon::parse($deliveryOrder->created_at)->format('d M Y, H:i') }}
                </p>
            </div>

            <div class="flex flex-wrap gap-2 mt-4 md:mt-0">
                <a href="{{ route('penjualan.delivery-order.print', $deliveryOrder->id) }}" target="_blank"
                    class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak
                </a>

                @if (auth()->user()->hasPermission('delivery_order.edit') && $deliveryOrder->status == 'draft')
                    <a href="{{ route('penjualan.delivery-order.edit', $deliveryOrder->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md text-sm font-medium text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Edit
                    </a>
                @endif

                <!-- Status Actions Dropdown -->
                @if (auth()->user()->hasPermission('delivery_order.process') || auth()->user()->hasPermission('delivery_order.cancel'))
                    <div class="relative" x-cloak>
                        <button @click="statusDropdownOpen = !statusDropdownOpen" type="button"
                            class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
                            <span>Tindakan</span>
                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="statusDropdownOpen" @click.away="statusDropdownOpen = false"
                            class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:divide-gray-700 focus:outline-none z-10"
                            role="menu" aria-orientation="vertical"
                            style="display: none; z-index: 50; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);">

                            <div class="py-1" role="none">
                                <span class="block px-4 py-2 text-xs text-gray-500 dark:text-gray-400 uppercase">
                                    Status Saat Ini: <span
                                        class="font-semibold capitalize">{{ statusLabel($deliveryOrder->status) }}</span>
                                </span>
                            </div>

                            <div class="py-1" role="none">
                                @if ($deliveryOrder->status == 'draft')
                                    <!-- Process Delivery -->
                                    <form id="processDOForm"
                                        action="{{ route('penjualan.delivery-order.proses', $deliveryOrder->id) }}"
                                        method="POST">
                                        @csrf
                                    </form>
                                    <button @click="openConfirmationModal('process')"
                                        class="status-dropdown-item w-full text-left px-4 py-2 text-sm flex items-center transition-all space-x-2 hover:bg-gray-100 dark:hover:bg-gray-700"
                                        role="menuitem">
                                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                        <span class="text-blue-600 dark:text-blue-400">Proses Pengiriman</span>
                                    </button>

                                    <!-- Delete -->
                                    <form id="deleteDOForm"
                                        action="{{ route('penjualan.delivery-order.destroy', $deliveryOrder->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button @click="openConfirmationModal('delete')"
                                        class="status-dropdown-item w-full text-left px-4 py-2 text-sm flex items-center transition-all space-x-2 hover:bg-gray-100 dark:hover:bg-gray-700"
                                        role="menuitem">
                                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                        <span class="text-red-600 dark:text-red-400">Hapus</span>
                                    </button>
                                @endif

                                @if ($deliveryOrder->status == 'dikirim')
                                    <!-- Mark as Complete -->
                                    @if (auth()->user()->hasPermission('delivery_order.complete'))
                                        <button @click="showModal = true; statusDropdownOpen = false"
                                            class="status-dropdown-item w-full text-left px-4 py-2 text-sm flex items-center transition-all space-x-2 hover:bg-gray-100 dark:hover:bg-gray-700"
                                            role="menuitem">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                            <span class="text-emerald-600 dark:text-emerald-400">Tandai Selesai</span>
                                        </button>
                                    @endif

                                    <!-- Cancel Delivery Order -->
                                    @if (auth()->user()->hasPermission('delivery_order.cancel'))
                                        <form id="cancelDOForm"
                                            action="{{ route('penjualan.delivery-order.batalkan', $deliveryOrder->id) }}"
                                            method="POST">
                                            @csrf
                                        </form>
                                        <button
                                            @click="actionType = 'cancel'; confirmModal = true; statusDropdownOpen = false"
                                            class="status-dropdown-item w-full text-left px-4 py-2 text-sm flex items-center transition-all space-x-2 hover:bg-gray-100 dark:hover:bg-gray-700"
                                            role="menuitem">
                                            <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                                            <span class="text-yellow-600 dark:text-yellow-400">Batalkan
                                                Pengiriman</span>
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Order Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Information -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Pengiriman</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Info -->
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor DO</h3>
                                <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $deliveryOrder->nomor }}</p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ date('d F Y', strtotime($deliveryOrder->tanggal)) }}</p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Customer</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    @if ($deliveryOrder->customer)
                                        <a href="{{ route('master.pelanggan.show', $deliveryOrder->customer->id) }}"
                                            class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                            {{ $deliveryOrder->customer->company ?? $deliveryOrder->customer->nama }}
                                        </a>
                                    @else
                                        Customer tidak ditemukan
                                    @endif
                                </p>
                                @if ($deliveryOrder->customer && $deliveryOrder->customer->kode)
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $deliveryOrder->customer->kode }}
                                    </p>
                                @endif
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Sales Order</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    @if ($deliveryOrder->salesOrder)
                                        <a href="{{ route('penjualan.sales-order.show', $deliveryOrder->salesOrder->id) }}"
                                            class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                            {{ $deliveryOrder->salesOrder->nomor }}
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
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat Pengiriman
                                </h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $deliveryOrder->alamat_pengiriman }}
                                </p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Keterangan</h3>
                                <div
                                    class="mt-2 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg text-sm text-gray-700 dark:text-gray-300">
                                    {{ $deliveryOrder->keterangan ?: 'Tidak ada keterangan' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DO Details/Items Table -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Produk</h2>
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
                                        Produk
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Kuantitas
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Satuan
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Keterangan
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($deliveryOrder->deliveryOrderDetail as $index => $detail)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                            <div class="font-medium">{{ $detail->produk->kode ?? '-' }}</div>
                                            <div>{{ $detail->produk->nama ?? 'Produk tidak ditemukan' }}</div>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ number_format($detail->quantity, 2, ',', '.') }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $detail->produk->satuan->nama ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $detail->keterangan ?: '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"
                                            class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-gray-400">
                                            Tidak ada item
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column - Status, Actions and Other Info -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status & Info</h2>

                    <div class="space-y-4">
                        <!-- Status Timeline -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Progress
                                Pengiriman
                            </h3>
                            <div class="relative">
                                <div class="status-timeline flex justify-between items-center mb-6">
                                    <div class="relative z-10 flex flex-col items-center">
                                        <div
                                            class="transition-all duration-300 h-6 w-6 flex items-center justify-center rounded-full border-2 
                                            {{ $deliveryOrder->status == 'draft' ? 'bg-' . statusColor('draft') . '-500 border-' . statusColor('draft') . '-500 shadow-md shadow-' . statusColor('draft') . '-200 dark:shadow-' . statusColor('draft') . '-900/20' : ($deliveryOrder->status == 'batal' ? 'bg-gray-200 border-gray-300 dark:bg-gray-700 dark:border-gray-600' : 'bg-' . statusColor('draft') . '-500 border-' . statusColor('draft') . '-500') }}">
                                            @if ($deliveryOrder->status == 'draft')
                                                <svg class="h-3 w-3 text-white" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 11.414V14a1 1 0 102 0v-2.586l1.293 1.293a1 1 0 001.414-1.414l-3-3z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            @else
                                                <svg class="h-3 w-3 text-white" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <span
                                            class="text-xs mt-2 font-medium 
                                            {{ $deliveryOrder->status == 'draft' ? 'text-' . statusColor('draft') . '-600 dark:text-' . statusColor('draft') . '-400' : ($deliveryOrder->status == 'batal' ? 'text-gray-500 dark:text-gray-400' : 'text-' . statusColor('belum_dikirim') . '-600 dark:text-' . statusColor('belum_dikirim') . '-400') }}">
                                            Draft
                                        </span>
                                    </div>
                                    <div
                                        class="w-full h-0.5 
                                        {{ in_array($deliveryOrder->status, ['dikirim', 'diterima']) ? 'bg-' . statusColor('dikirim') . '-500' : 'bg-gray-200 dark:bg-gray-700' }}">
                                    </div>

                                    <div class="relative z-10 flex flex-col items-center">
                                        <div
                                            class="transition-all duration-300 h-6 w-6 flex items-center justify-center rounded-full border-2 
                                            {{ in_array($deliveryOrder->status, ['dikirim', 'diterima']) ? 'bg-' . statusColor('dikirim') . '-500 border-' . statusColor('dikirim') . '-500 shadow-md shadow-' . statusColor('dikirim') . '-200 dark:shadow-' . statusColor('dikirim') . '-900/20' : 'bg-gray-200 border-gray-300 dark:bg-gray-700 dark:border-gray-600' }}">
                                            @if ($deliveryOrder->status == 'dikirim')
                                                <svg class="h-3 w-3 text-white" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                                    <path
                                                        d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" />
                                                </svg>
                                            @elseif ($deliveryOrder->status == 'diterima')
                                                <svg class="h-3 w-3 text-white" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <span
                                            class="text-xs mt-2 font-medium 
                                            {{ in_array($deliveryOrder->status, ['dikirim', 'diterima']) ? 'text-' . statusColor('dikirim') . '-600 dark:text-' . statusColor('dikirim') . '-400' : 'text-gray-500 dark:text-gray-400' }}">
                                            <span class="whitespace-nowrap">Proses Pengiriman</span>
                                        </span>
                                    </div>
                                    <div
                                        class="w-full h-0.5 
                                        {{ $deliveryOrder->status == 'diterima' ? 'bg-' . statusColor('dikirim') . '-500' : 'bg-gray-200 dark:bg-gray-700' }}">
                                    </div>

                                    <div class="relative z-10 flex flex-col items-center">
                                        <div
                                            class="transition-all duration-300 h-6 w-6 flex items-center justify-center rounded-full border-2 
                                            {{ $deliveryOrder->status == 'diterima' ? 'bg-' . statusColor('selesai') . '-500 border-' . statusColor('selesai') . '-500 shadow-md shadow-' . statusColor('selesai') . '-200 dark:shadow-' . statusColor('selesai') . '-900/20' : 'bg-gray-200 border-gray-300 dark:bg-gray-700 dark:border-gray-600' }}">
                                            @if ($deliveryOrder->status == 'diterima')
                                                <svg class="h-3 w-3 text-white" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <span
                                            class="text-xs mt-2 font-medium 
                                            {{ $deliveryOrder->status == 'dikirim' ? 'text-' . statusColor('selesai') . '-600 dark:text-' . statusColor('selesai') . '-400' : 'text-gray-500 dark:text-gray-400' }}">
                                            Selesai
                                        </span>
                                    </div>
                                </div>

                                @if ($deliveryOrder->status == 'batal')
                                    <div
                                        class="bg-red-100 dark:bg-red-900/20 border border-red-200 dark:border-red-900/30 rounded-md p-3 mt-2">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-red-800 dark:text-red-400">
                                                    Delivery Order telah dibatalkan
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if ($deliveryOrder->nama_penerima)
                            <div class="pt-2 mt-4 border-t border-gray-200 dark:border-gray-700">
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Informasi
                                    Penerimaan</h3>
                                <div class="space-y-2">
                                    <div>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Penerima:</span>
                                        <p class="text-sm text-gray-900 dark:text-white">
                                            {{ $deliveryOrder->nama_penerima }}</p>
                                    </div>
                                    <div>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Tanggal
                                            Diterima:</span>
                                        <p class="text-sm text-gray-900 dark:text-white">
                                            {{ date('d F Y', strtotime($deliveryOrder->tanggal_diterima)) }}
                                        </p>
                                    </div>
                                    @if ($deliveryOrder->keterangan_penerima)
                                        <div>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">Keterangan:</span>
                                            <p class="text-sm text-gray-900 dark:text-white">
                                                {{ $deliveryOrder->keterangan_penerima }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Information Card -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi</h2>
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat Oleh</h3>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $deliveryOrder->user->name ?? 'User tidak ditemukan' }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $deliveryOrder->created_at->format('d F Y H:i') }}
                            </p>
                        </div>

                        @if ($deliveryOrder->updatedBy)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diupdate
                                </h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $deliveryOrder->updatedBy->name ?? 'User tidak ditemukan' }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $deliveryOrder->updated_at->format('d F Y H:i') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Related Sales Order if exists -->
                @if ($deliveryOrder->salesOrder)
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Sales Order Terkait
                            </h2>
                        </div>
                        <div class="p-6">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Sales Order
                            </h3>
                            <a href="{{ route('penjualan.sales-order.show', $deliveryOrder->salesOrder->id) }}"
                                class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                <div
                                    class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-indigo-600 dark:text-indigo-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="ml-3">

                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $deliveryOrder->salesOrder->nomor }}</div>
                                    <div class="text-xs font-medium text-gray-900 dark:text-white">
                                        {{ $deliveryOrder->salesOrder->customer->company ?? ($deliveryOrder->salesOrder->customer->nama ?? 'Customer tidak ditemukan') }}
                                    </div>

                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ date('d F Y', strtotime($deliveryOrder->salesOrder->tanggal)) }}
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Modal Tandai Selesai -->
        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
            role="dialog" aria-modal="true" x-cloak>
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div x-show="showModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
                </div>

                <!-- Modal panel -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <!-- Modal header with icon -->
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/20 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                    id="modal-title">
                                    Konfirmasi Pengiriman Selesai
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Silakan isi informasi penerimaan barang untuk menyelesaikan proses
                                        pengiriman.
                                        Pastikan data yang dimasukkan sudah benar dan sesuai.
                                    </p>

                                    <form action="{{ route('penjualan.delivery-order.selesai', $deliveryOrder->id) }}"
                                        method="POST" id="formComplete" class="mt-5 space-y-4">
                                        @csrf

                                        <!-- Recipient information section -->
                                        <div class="bg-gray-50 dark:bg-gray-700/30 p-3 rounded-lg space-y-4">
                                            <!-- Recipient name -->
                                            <div>
                                                <label for="nama_penerima"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                    Nama Penerima <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="nama_penerima" id="nama_penerima"
                                                    required
                                                    class="shadow-sm block w-full focus:ring-primary-500 focus:border-primary-500 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                                    placeholder="Masukkan nama lengkap penerima">
                                            </div>

                                            <!-- Date received -->
                                            <div>
                                                <label for="tanggal_diterima"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                    Tanggal Diterima <span class="text-red-500">*</span>
                                                </label>
                                                <input type="date" name="tanggal_diterima" id="tanggal_diterima"
                                                    required value="{{ date('Y-m-d') }}"
                                                    class="shadow-sm block w-full focus:ring-primary-500 focus:border-primary-500 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                            </div>

                                            <!-- Additional notes -->
                                            <div>
                                                <label for="keterangan_penerima"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                    Catatan Penerimaan <span
                                                        class="text-gray-400 font-normal">(opsional)</span>
                                                </label>
                                                <textarea name="keterangan_penerima" id="keterangan_penerima" rows="3"
                                                    class="shadow-sm block w-full focus:ring-primary-500 focus:border-primary-500 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                                    placeholder="Masukkan catatan tambahan jika ada (kondisi barang, kelengkapan, dsb)"></textarea>
                                            </div>
                                        </div>

                                        <!-- Form note -->
                                        <div class="text-xs text-gray-500 dark:text-gray-400 italic mt-2">
                                            <span class="text-red-500">*</span> Wajib diisi
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer with actions -->
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" onclick="document.getElementById('formComplete').submit();"
                            class="w-full inline-flex justify-center items-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            <svg class="mr-2 -ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Tandai Selesai
                        </button>
                        <button type="button" @click="showModal = false"
                            class="mt-3 w-full inline-flex justify-center items-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            <svg class="mr-2 -ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal for Status Changes -->
        <div x-show="confirmModal" class="fixed inset-0 overflow-y-auto z-50" x-cloak
            aria-labelledby="confirm-modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div @click="confirmModal = false; isLoading = false;" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
                </div>

                <!-- This element is to trick the browser into centering the modal contents. -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full"
                                :class="{
                                    'bg-blue-100 dark:bg-blue-900/20': actionType == 'process',
                                    'bg-red-100 dark:bg-red-900/20': actionType == 'delete',
                                    'bg-yellow-100 dark:bg-yellow-900/20': actionType == 'cancel'
                                }">
                                <svg class="h-6 w-6"
                                    :class="{
                                        'text-blue-600 dark:text-blue-400': actionType == 'process',
                                        'text-red-600 dark:text-red-400': actionType == 'delete',
                                        'text-yellow-600 dark:text-yellow-400': actionType == 'cancel'
                                    }"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        x-show="actionType == 'process'" d="M5 13l4 4L19 7" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        x-show="actionType == 'delete' || actionType == 'cancel'"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                    id="modal-title">
                                    <span x-show="actionType == 'process'">Proses Pengiriman</span>
                                    <span x-show="actionType == 'delete'">Hapus Delivery Order</span>
                                    <span x-show="actionType == 'cancel'">Batalkan Delivery Order</span>
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        <span x-show="actionType == 'process'">
                                            Apakah Anda yakin ingin memproses pengiriman ini? Stok akan dikurangi
                                            sesuai
                                            dengan kuantitas barang dalam delivery order.
                                        </span>
                                        <span x-show="actionType == 'delete'">
                                            Apakah Anda yakin ingin menghapus delivery order ini? Tindakan ini tidak
                                            dapat dibatalkan.
                                        </span>
                                        <span x-show="actionType == 'cancel'">
                                            Apakah Anda yakin ingin membatalkan delivery order ini? Status akan
                                            diubah
                                            menjadi dibatalkan.
                                        </span>
                                    </p>

                                    <!-- Warning for delete/cancel -->
                                    <div class="mt-3 p-3 bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-800/30 rounded-md"
                                        x-show="actionType == 'delete' || actionType == 'cancel'">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-amber-400" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-amber-800 dark:text-amber-300">
                                                    Perhatian Penting
                                                </h3>
                                                <div class="mt-2 text-sm text-amber-700 dark:text-amber-300">
                                                    <span x-show="actionType == 'delete'">
                                                        Menghapus delivery order akan menghilangkan semua data
                                                        terkait.
                                                        Jika DO sudah memiliki referensi ke dokumen lain, sebaiknya
                                                        gunakan opsi batalkan sebagai gantinya.
                                                    </span>
                                                    <span x-show="actionType == 'cancel'">
                                                        Membatalkan delivery order akan mengubah status menjadi
                                                        'dibatalkan' dan jika sudah diproses, stok akan
                                                        dikembalikan.
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="handleConfirmAction()"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white sm:ml-3 sm:w-auto sm:text-sm transition-colors"
                            :class="{
                                'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500': actionType == 'process',
                                'bg-red-600 hover:bg-red-700 focus:ring-red-500': actionType == 'delete',
                                'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500': actionType == 'cancel'
                            }">
                            <span x-show="actionType == 'process'">Proses Pengiriman</span>
                            <span x-show="actionType == 'delete'">Hapus</span>
                            <span x-show="actionType == 'cancel'">Batalkan</span>
                        </button>
                        <button type="button" @click="confirmModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Area -->
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90" class="fixed bottom-0 right-0 mb-4 mr-4 z-50">
                <div
                    class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 dark:border-green-500 p-4 rounded shadow-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700 dark:text-green-200">
                                {{ session('success') }}
                            </p>
                        </div>
                        <div class="ml-auto pl-3">
                            <div class="-mx-1.5 -my-1.5">
                                <button @click="show = false"
                                    class="inline-flex text-green-500 hover:text-green-600 focus:outline-none">
                                    <span class="sr-only">Dismiss</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90" class="fixed bottom-0 right-0 mb-4 mr-4 z-50">
                <div
                    class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 dark:border-red-500 p-4 rounded shadow-lg">
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
                            <p class="text-sm text-red-700 dark:text-red-200">
                                {{ session('error') }}
                            </p>
                        </div>
                        <div class="ml-auto pl-3">
                            <div class="-mx-1.5 -my-1.5">
                                <button @click="show = false"
                                    class="inline-flex text-red-500 hover:text-red-600 focus:outline-none">
                                    <span class="sr-only">Dismiss</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                // Form validation for completion modal
                Alpine.data('completeDeliveryForm', () => ({
                    formValid: false,
                    namaValid: false,
                    tanggalValid: false,

                    init() {
                        // Initialize form validation
                        this.validateForm();

                        // Set up event listeners for inputs
                        document.getElementById('nama_penerima')?.addEventListener('input', () => {
                            this.namaValid = document.getElementById('nama_penerima').value
                                .trim() !== '';
                            this.validateForm();
                        });

                        document.getElementById('tanggal_diterima')?.addEventListener('input', () => {
                            this.tanggalValid = document.getElementById('tanggal_diterima')
                                .value !== '';
                            this.validateForm();
                        });
                    },

                    validateForm() {
                        if (document.getElementById('nama_penerima')) {
                            this.namaValid = document.getElementById('nama_penerima').value.trim() !== '';
                            this.tanggalValid = document.getElementById('tanggal_diterima').value !== '';
                            this.formValid = this.namaValid && this.tanggalValid;
                        }
                    },

                    submitForm() {
                        this.validateForm();
                        if (this.formValid) {
                            document.getElementById('formComplete').submit();
                        } else {
                            // Highlight invalid fields
                            if (!this.namaValid) {
                                document.getElementById('nama_penerima').classList.add('border-red-500');
                            }
                            if (!this.tanggalValid) {
                                document.getElementById('tanggal_diterima').classList.add('border-red-500');
                            }
                        }
                    }
                }));

                // Delivery Order Status Management 
                Alpine.data('deliveryOrderActions', () => ({
                    statusDropdownOpen: false,
                    confirmModal: false,
                    showModal: false,
                    actionType: null,
                    currentStatus: '',

                    init() {
                        // Get initial status from the element
                        this.currentStatus = this.$el.getAttribute('data-current-status') || '';
                    },

                    handleConfirmAction() {
                        if (this.actionType === 'process') {
                            document.getElementById('processDOForm').submit();
                        } else if (this.actionType === 'delete') {
                            document.getElementById('deleteDOForm').submit();
                        } else if (this.actionType === 'cancel') {
                            document.getElementById('cancelDOForm').submit();
                        }

                        // Close the modal
                        this.confirmModal = false;
                    },

                    openCompleteModal() {
                        this.statusDropdownOpen = false;
                        this.showModal = true;
                    },

                    openConfirmationModal(type) {
                        this.statusDropdownOpen = false;
                        this.actionType = type;
                        this.confirmModal = true;
                    }
                }));
            });

            // Add confirmation for delete/cancel actions
            document.addEventListener('DOMContentLoaded', function() {
                const dangerForms = document.querySelectorAll('form[data-confirm-action]');
                dangerForms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        const message = this.getAttribute('data-confirm-message') ||
                            'Apakah Anda yakin ingin melakukan tindakan ini?';
                        if (!confirm(message)) {
                            e.preventDefault();
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
