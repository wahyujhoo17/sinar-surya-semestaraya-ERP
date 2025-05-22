<x-app-layout>
    @php
        // Helper functions are autoloaded by composer

        // Helper function to get status color for payment
        function paymentStatusColor($status)
        {
            switch ($status) {
                case 'belum_bayar':
                    return 'red';
                case 'sebagian':
                    return 'amber';
                case 'lunas':
                    return 'emerald';
                default:
                    return 'primary';
            }
        }

        // Helper function to get status color for delivery
        function deliveryStatusColor($status)
        {
            switch ($status) {
                case 'belum_dikirim':
                    return 'red';
                case 'sebagian':
                    return 'amber';
                case 'dikirim':
                    return 'emerald';
                default:
                    return 'primary';
            }
        }

        // Helper function to get status label
        function statusLabel($key, $type = 'payment')
        {
            if ($type === 'payment') {
                $statuses = [
                    'belum_bayar' => 'Belum Bayar',
                    'sebagian' => 'Sebagian',
                    'lunas' => 'Lunas',
                ];
            } else {
                $statuses = [
                    'belum_dikirim' => 'Belum Dikirim',
                    'sebagian' => 'Sebagian',
                    'dikirim' => 'Dikirim',
                ];
            }

            return $statuses[$key] ?? ucfirst($key);
        }

        // Helper function to get activity color
        function getActivityColor($activity)
        {
            switch ($activity) {
                case 'create':
                    return 'green';
                case 'update':
                    return 'blue';
                case 'delete':
                    return 'red';
                case 'change_status':
                    return 'amber';
                default:
                    return 'gray';
            }
        }

        // Helper function to get activity icon
        function getActivityIcon($activity)
        {
            switch ($activity) {
                case 'create':
                    return '<svg class="h-4 w-4 text-green-500 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>';
                case 'update':
                    return '<svg class="h-4 w-4 text-blue-500 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>';
                case 'delete':
                    return '<svg class="h-4 w-4 text-red-500 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
                case 'change_status':
                    return '<svg class="h-4 w-4 text-amber-500 dark:text-amber-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>';
                default:
                    return '<svg class="h-4 w-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
            }
        }

        // Get the badge color and icon for payment status
        $paymentStatusBgColor =
            'bg-' .
            paymentStatusColor($salesOrder->status_pembayaran) .
            '-100 dark:bg-' .
            paymentStatusColor($salesOrder->status_pembayaran) .
            '-900/30';
        $paymentStatusTextColor =
            'text-' .
            paymentStatusColor($salesOrder->status_pembayaran) .
            '-800 dark:text-' .
            paymentStatusColor($salesOrder->status_pembayaran) .
            '-300';
        $paymentStatusIconColor =
            'text-' .
            paymentStatusColor($salesOrder->status_pembayaran) .
            '-500 dark:text-' .
            paymentStatusColor($salesOrder->status_pembayaran) .
            '-400';

        // Get the badge color and icon for delivery status
        $deliveryStatusBgColor =
            'bg-' .
            deliveryStatusColor($salesOrder->status_pengiriman) .
            '-100 dark:bg-' .
            deliveryStatusColor($salesOrder->status_pengiriman) .
            '-900/30';
        $deliveryStatusTextColor =
            'text-' .
            deliveryStatusColor($salesOrder->status_pengiriman) .
            '-800 dark:text-' .
            deliveryStatusColor($salesOrder->status_pengiriman) .
            '-300';
        $deliveryStatusIconColor =
            'text-' .
            deliveryStatusColor($salesOrder->status_pengiriman) .
            '-500 dark:text-' .
            deliveryStatusColor($salesOrder->status_pengiriman) .
            '-400';
    @endphp

    @push('styles')
        <style>
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

            .card {
                transition: all 0.2s ease;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            }

            .card:hover {
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.12);
            }

            .badge-dot {
                height: 8px;
                width: 8px;
                border-radius: 50%;
                display: inline-block;
                margin-right: 6px;
            }

            .timeline-item {
                position: relative;
                padding-left: 30px;
            }

            .timeline-item:before {
                content: "";
                position: absolute;
                left: 0;
                top: 0;
                bottom: 0;
                width: 2px;
                background-color: #e5e7eb;
            }

            .dark .timeline-item:before {
                background-color: #374151;
            }

            .timeline-dot {
                position: absolute;
                left: -6px;
                top: 0;
                height: 14px;
                width: 14px;
                border-radius: 50%;
                z-index: 10;
            }

            .summary-value {
                font-weight: 500;
                font-size: 1.125rem;
            }

            .summary-label {
                font-size: 0.875rem;
                color: #6b7280;
            }

            .dark .summary-label {
                color: #9ca3af;
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
                            <a href="{{ route('penjualan.sales-order.index') }}"
                                class="ml-1 text-gray-500 hover:text-gray-700 md:ml-2 dark:text-gray-400 dark:hover:text-white">
                                Sales Order
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
                                class="ml-1 font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{ $salesOrder->nomor }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Sales Order Summary Card -->
        <div
            class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 mb-6 overflow-hidden">
            <div class="p-6">
                <div class="lg:flex lg:items-center lg:justify-between">
                    <!-- Order Info -->
                    <div class="mb-4 lg:mb-0">
                        <div class="flex items-center gap-3 mb-2">
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                                {{ $salesOrder->nomor }}
                            </h1>
                            <div class="flex gap-2">
                                <div
                                    class="flex items-center px-3 py-1 rounded-full {{ $paymentStatusBgColor }} {{ $paymentStatusTextColor }}">
                                    <span
                                        class="flex w-2 h-2 mr-1.5 rounded-full bg-{{ paymentStatusColor($salesOrder->status_pembayaran) }}-500"></span>
                                    <span
                                        class="text-sm font-medium">{{ statusLabel($salesOrder->status_pembayaran, 'payment') }}</span>
                                </div>
                                <div
                                    class="flex items-center px-3 py-1 rounded-full {{ $deliveryStatusBgColor }} {{ $deliveryStatusTextColor }}">
                                    <span
                                        class="flex w-2 h-2 mr-1.5 rounded-full bg-{{ deliveryStatusColor($salesOrder->status_pengiriman) }}-500"></span>
                                    <span
                                        class="text-sm font-medium">{{ statusLabel($salesOrder->status_pengiriman, 'delivery') }}</span>
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex flex-wrap items-center text-sm text-gray-600 dark:text-gray-400 gap-x-6 gap-y-1">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ \Carbon\Carbon::parse($salesOrder->tanggal)->format('d M Y') }}
                            </div>
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ $salesOrder->customer->nama }}
                            </div>
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                </svg>
                                Total: Rp {{ number_format($salesOrder->total, 0, ',', '.') }}
                            </div>
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ \Carbon\Carbon::parse($salesOrder->created_at)->format('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-2 mt-4 lg:mt-0 lg:ml-4" x-data="{ statusDropdownOpen: false, confirmModal: false, formData: { status_pembayaran: '{{ $salesOrder->status_pembayaran }}', status_pengiriman: '{{ $salesOrder->status_pengiriman }}', catatan_status: '' } }">
                        <!-- Print Button -->
                        <a href="{{ route('penjualan.sales-order.pdf', $salesOrder->id) }}" target="_blank"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Cetak
                        </a>

                        <!-- Change Status Button -->
                        <button type="button" @click="statusDropdownOpen = !statusDropdownOpen"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors duration-200 relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 11l5-5m0 0l5 5m-5-5v12" />
                            </svg>
                            Ubah Status
                        </button>

                        <!-- Status Change Dropdown -->
                        <div x-show="statusDropdownOpen" @click.away="statusDropdownOpen = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-12 w-96 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-50"
                            style="margin-top: 70px; margin-right: 16px;">
                            <div class="p-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Ubah Status</h3>
                                <form @submit.prevent="confirmModal = true">
                                    <div class="mb-4">
                                        <label for="status_pembayaran"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status
                                            Pembayaran</label>
                                        <select x-model="formData.status_pembayaran" id="status_pembayaran"
                                            name="status_pembayaran"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white">
                                            <option value="belum_bayar"
                                                :selected="formData.status_pembayaran == 'belum_bayar'">
                                                Belum Bayar</option>
                                            <option value="sebagian"
                                                :selected="formData.status_pembayaran == 'sebagian'">
                                                Sebagian</option>
                                            <option value="lunas" :selected="formData.status_pembayaran == 'lunas'">
                                                Lunas
                                            </option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label for="status_pengiriman"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status
                                            Pengiriman</label>
                                        <select x-model="formData.status_pengiriman" id="status_pengiriman"
                                            name="status_pengiriman"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white">
                                            <option value="belum_dikirim"
                                                :selected="formData.status_pengiriman == 'belum_dikirim'">Belum Dikirim
                                            </option>
                                            <option value="sebagian"
                                                :selected="formData.status_pengiriman == 'sebagian'">
                                                Sebagian</option>
                                            <option value="dikirim"
                                                :selected="formData.status_pengiriman == 'dikirim'">Dikirim
                                            </option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label for="catatan_status"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan
                                            (Opsional)</label>
                                        <textarea x-model="formData.catatan_status" id="catatan_status" name="catatan_status" rows="2"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white"></textarea>
                                    </div>
                                    <button type="submit"
                                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                        Simpan Perubahan
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Confirmation Modal -->
                        <div x-show="confirmModal" class="fixed inset-0 overflow-y-auto z-50" style="display: none;">
                            <div
                                class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                </div>
                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                    aria-hidden="true">&#8203;</span>
                                <div
                                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <div class="sm:flex sm:items-start">
                                            <div
                                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 dark:bg-yellow-900 sm:mx-0 sm:h-10 sm:w-10">
                                                <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                            </div>
                                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                <h3
                                                    class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                                                    Konfirmasi Perubahan Status
                                                </h3>
                                                <div class="mt-2">
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                                        Apakah Anda yakin ingin mengubah status Sales Order ini?
                                                    </p>
                                                    <div class="mt-3 space-y-2">
                                                        <p class="text-sm text-gray-700 dark:text-gray-300">
                                                            <span class="font-semibold">Status Pembayaran:</span>
                                                            <span
                                                                x-text="statusLabel(formData.status_pembayaran, 'payment')"></span>
                                                        </p>
                                                        <p class="text-sm text-gray-700 dark:text-gray-300">
                                                            <span class="font-semibold">Status Pengiriman:</span>
                                                            <span
                                                                x-text="statusLabel(formData.status_pengiriman, 'delivery')"></span>
                                                        </p>
                                                        <p class="text-sm text-gray-700 dark:text-gray-300"
                                                            x-show="formData.catatan_status">
                                                            <span class="font-semibold">Catatan:</span>
                                                            <span x-text="formData.catatan_status"></span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <form method="POST"
                                            action="{{ route('penjualan.sales-order.changeStatus', $salesOrder->id) }}">
                                            @csrf
                                            <input type="hidden" name="status_pembayaran"
                                                :value="formData.status_pembayaran">
                                            <input type="hidden" name="status_pengiriman"
                                                :value="formData.status_pengiriman">
                                            <input type="hidden" name="catatan_status"
                                                :value="formData.catatan_status">
                                            <button type="submit"
                                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                                                Konfirmasi
                                            </button>
                                        </form>
                                        <button type="button" @click="confirmModal = false"
                                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                                            Batal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Button (only if allowed) -->
                        @if (
                            !$salesOrder->deliveryOrders()->exists() &&
                                !$salesOrder->invoices()->exists() &&
                                !$salesOrder->workOrders()->exists())
                            <a href="{{ route('penjualan.sales-order.edit', $salesOrder->id) }}"
                                class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md text-sm font-medium text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                Edit
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Customer Info & Items -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Customer Information -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 card">
                    <div
                        class="border-b border-gray-200 dark:border-gray-700 px-5 py-4 flex justify-between items-center">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Sales Order</h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-6">
                            <div class="border-l-4 border-indigo-500 pl-3 py-1">
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Customer</h3>
                                <div class="mt-2 text-sm text-gray-900 dark:text-white">
                                    <p class="font-medium text-base">{{ $salesOrder->customer->nama }}</p>
                                    @if ($salesOrder->customer->company)
                                        <p>{{ $salesOrder->customer->company }}</p>
                                    @endif

                                    @if ($salesOrder->customer->kontak_person)
                                        <p class="flex items-center mt-1">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 mr-1.5 text-gray-500" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span class="text-gray-700 dark:text-gray-300">Kontak Person:</span>
                                            <span class="ml-1">{{ $salesOrder->customer->kontak_person }}</span>
                                        </p>
                                    @endif

                                    @if ($salesOrder->customer->no_hp_kontak)
                                        <p class="flex items-center mt-1">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 mr-1.5 text-gray-500" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                            <span class="text-gray-700 dark:text-gray-300">HP Kontak:</span>
                                            <span class="ml-1">{{ $salesOrder->customer->no_hp_kontak }}</span>
                                        </p>
                                    @endif

                                    <p class="flex items-center mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        {{ $salesOrder->customer->telepon }}
                                    </p>
                                    <p class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        {{ $salesOrder->customer->email }}
                                    </p>
                                </div>
                            </div>
                            <div class="border-l-4 border-green-500 pl-3 py-1">
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat Pengiriman</h3>
                                <div class="mt-2 text-sm text-gray-900 dark:text-white">
                                    <p class="flex items-start">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 mr-1.5 text-gray-500 flex-shrink-0 mt-0.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>{{ $salesOrder->alamat_pengiriman ?? ($salesOrder->customer->alamat_utama ?? '-') }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="border-l-4 border-purple-500 pl-3 py-1">
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Informasi Dokumen</h3>
                                <div class="mt-2 text-sm text-gray-900 dark:text-white">
                                    <p class="flex items-center mb-1">
                                        <span class="w-24 text-gray-500 dark:text-gray-400">Nomor:</span>
                                        <span class="font-medium">{{ $salesOrder->nomor }}</span>
                                    </p>
                                    <p class="flex items-center mb-1">
                                        <span class="w-24 text-gray-500 dark:text-gray-400">Tanggal:</span>
                                        <span>{{ \Carbon\Carbon::parse($salesOrder->tanggal)->format('d/m/Y') }}</span>
                                    </p>
                                    <p class="flex items-center mb-1">
                                        <span class="w-24 text-gray-500 dark:text-gray-400">Tanggal Kirim:</span>
                                        <span>{{ $salesOrder->tanggal_kirim ? \Carbon\Carbon::parse($salesOrder->tanggal_kirim)->format('d/m/Y') : '-' }}</span>
                                    </p>
                                    @if ($salesOrder->quotation)
                                        <p class="flex items-center">
                                            <span class="w-24 text-gray-500 dark:text-gray-400">Quotation:</span>
                                            <a href="{{ route('penjualan.quotation.show', $salesOrder->quotation->id) }}"
                                                class="text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 underline">
                                                {{ $salesOrder->quotation->nomor }}
                                            </a>
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="border-l-4 border-amber-500 pl-3 py-1">
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h3>
                                <div class="mt-2 text-sm text-gray-900 dark:text-white space-y-3">
                                    <p class="flex items-center">
                                        <span class="w-24 text-gray-500 dark:text-gray-400">Pembayaran:</span>
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $paymentStatusBgColor }} {{ $paymentStatusTextColor }}">
                                            <span
                                                class="badge-dot bg-{{ paymentStatusColor($salesOrder->status_pembayaran) }}-500 mr-1"></span>
                                            {{ statusLabel($salesOrder->status_pembayaran, 'payment') }}
                                        </span>
                                    </p>
                                    <p class="flex items-center">
                                        <span class="w-24 text-gray-500 dark:text-gray-400">Pengiriman:</span>
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $deliveryStatusBgColor }} {{ $deliveryStatusTextColor }}">
                                            <span
                                                class="badge-dot bg-{{ deliveryStatusColor($salesOrder->status_pengiriman) }}-500 mr-1"></span>
                                            {{ statusLabel($salesOrder->status_pengiriman, 'delivery') }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items Table -->
                <div
                    class="card bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                    <div
                        class="border-b border-gray-200 dark:border-gray-700 px-5 py-4 flex justify-between items-center">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Item Produk</h2>
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $salesOrder->details->count() }}
                            item</span>
                    </div>
                    <div class="overflow-x-auto table-wrapper">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th scope="col"
                                        class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Produk
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Deskripsi
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3.5 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Qty
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3.5 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Harga
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3.5 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Diskon
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3.5 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Subtotal
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                                @foreach ($salesOrder->details as $detail)
                                    <tr
                                        class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors duration-150">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            <div class="font-medium">
                                                {{ $detail->produk->nama ?? 'Produk tidak ditemukan' }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $detail->produk->kode ?? '' }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                            {{ $detail->deskripsi ?? '-' }}
                                        </td>
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm text-center text-gray-900 dark:text-white">
                                            {{ number_format($detail->quantity, 2, ',', '.') }}
                                            {{ $detail->satuan->nama ?? '' }}
                                        </td>
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                                            {{ number_format($detail->harga, 0, ',', '.') }}
                                        </td>
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                                            @if ($detail->diskon_persen > 0)
                                                {{ number_format($detail->diskon_persen, 2, ',', '.') }}%
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium text-gray-900 dark:text-white">
                                            {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <td colspan="5"
                                        class="px-4 py-3 text-sm text-right font-medium text-gray-700 dark:text-gray-300">
                                        Subtotal:
                                    </td>
                                    <td class="px-4 py-3 text-sm text-right font-medium text-gray-900 dark:text-white">
                                        {{ number_format($salesOrder->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @if ($salesOrder->diskon_nominal > 0)
                                    <tr>
                                        <td colspan="5"
                                            class="px-4 py-3 text-sm text-right font-medium text-gray-700 dark:text-gray-300">
                                            Diskon Global:
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm text-right font-medium text-green-600 dark:text-green-400">
                                            -{{ number_format($salesOrder->diskon_nominal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endif
                                @if ($salesOrder->ppn > 0)
                                    <tr>
                                        <td colspan="5"
                                            class="px-4 py-3 text-sm text-right font-medium text-gray-700 dark:text-gray-300">
                                            PPN ({{ $salesOrder->ppn }}%):
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm text-right font-medium text-blue-600 dark:text-blue-400">
                                            {{ number_format(($salesOrder->subtotal - $salesOrder->diskon_nominal) * ($salesOrder->ppn / 100), 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="5"
                                        class="px-4 py-3 text-base text-right font-bold text-gray-900 dark:text-white">
                                        Total:
                                    </td>
                                    <td class="px-4 py-3 text-base text-right font-bold text-gray-900 dark:text-white">
                                        {{ number_format($salesOrder->total, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 card">
                    <div class="border-b border-gray-200 dark:border-gray-700 px-5 py-4">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Ringkasan Pembayaran</h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <div class="p-4 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg">
                                <div class="text-indigo-500 dark:text-indigo-400 font-medium mb-1 text-sm">Total Order
                                </div>
                                <div class="text-gray-900 dark:text-white font-bold text-lg">Rp
                                    {{ number_format($salesOrder->total, 0, ',', '.') }}</div>
                            </div>

                            <div class="p-4 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg">
                                <div class="text-emerald-500 dark:text-emerald-400 font-medium mb-1 text-sm">Total
                                    Dibayar</div>
                                <div class="text-gray-900 dark:text-white font-bold text-lg">
                                    @php
                                        $totalDibayar = $salesOrder->invoices
                                            ->where('status_pembayaran', 'lunas')
                                            ->sum('total');
                                    @endphp
                                    Rp {{ number_format($totalDibayar, 0, ',', '.') }}
                                </div>
                            </div>

                            <div class="p-4 bg-amber-50 dark:bg-amber-900/30 rounded-lg">
                                <div class="text-amber-500 dark:text-amber-400 font-medium mb-1 text-sm">Sisa Tagihan
                                </div>
                                <div class="text-gray-900 dark:text-white font-bold text-lg">
                                    Rp {{ number_format($salesOrder->total - $totalDibayar, 0, ',', '.') }}
                                </div>
                            </div>

                            <div class="p-4 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                                <div class="text-blue-500 dark:text-blue-400 font-medium mb-1 text-sm">Status
                                    Pengiriman</div>
                                <div class="text-gray-900 dark:text-white font-semibold flex items-center">
                                    <span
                                        class="badge-dot bg-{{ deliveryStatusColor($salesOrder->status_pengiriman) }}-500 mr-1"></span>
                                    {{ statusLabel($salesOrder->status_pengiriman, 'delivery') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes Section -->
                @if ($salesOrder->catatan || $salesOrder->syarat_ketentuan)
                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 card">
                        <div class="border-b border-gray-200 dark:border-gray-700 px-5 py-4">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Catatan & Syarat Ketentuan
                            </h2>
                        </div>
                        <div class="p-5 space-y-4">
                            @if ($salesOrder->catatan)
                                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4">
                                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan</h3>
                                    <p class="text-sm text-gray-900 dark:text-white whitespace-pre-line">
                                        {{ $salesOrder->catatan }}</p>
                                </div>
                            @endif

                            @if ($salesOrder->syarat_ketentuan)
                                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4">
                                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Syarat dan
                                        Ketentuan</h3>
                                    <p class="text-sm text-gray-900 dark:text-white whitespace-pre-line">
                                        {{ $salesOrder->syarat_ketentuan }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column - Related Documents & Activity Logs -->
            <div class="space-y-6">
                <!-- Related Documents -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 card">
                    <div
                        class="border-b border-gray-200 dark:border-gray-700 px-5 py-4 flex justify-between items-center">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Dokumen Terkait</h2>
                        <span
                            class="px-2.5 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-xs">
                            {{ ($salesOrder->quotation ? 1 : 0) +
                                $salesOrder->workOrders->count() +
                                $salesOrder->deliveryOrders->count() +
                                $salesOrder->invoices->count() }}
                            dokumen
                        </span>
                    </div>
                    <div class="p-5">
                        <div class="space-y-5">
                            <!-- Quotation (Source Document) -->
                            <div>
                                <h3
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Quotation
                                </h3>
                                @if ($salesOrder->quotation)
                                    <a href="{{ route('penjualan.quotation.show', $salesOrder->quotation->id) }}"
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
                                                {{ $salesOrder->quotation->nomor }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($salesOrder->quotation->tanggal)->format('d/m/Y') }}
                                            </div>
                                        </div>
                                    </a>
                                @else
                                    <div
                                        class="text-sm text-gray-500 dark:text-gray-400 italic p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                                        Tidak ada quotation terkait</div>
                                @endif
                            </div>

                            <!-- Work Orders -->
                            <div>
                                <h3
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Work Order <span
                                        class="ml-1 text-xs text-gray-400">({{ $salesOrder->workOrders->count() }})</span>
                                </h3>
                                @if ($salesOrder->workOrders && $salesOrder->workOrders->count() > 0)
                                    <div class="space-y-2">
                                        @foreach ($salesOrder->workOrders as $workOrder)
                                            <a href="{{ route('produksi.work-order.show', $workOrder->id) }}"
                                                class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                                <div
                                                    class="flex-shrink-0 w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5 text-amber-600 dark:text-amber-400"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $workOrder->nomor }}</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ \Carbon\Carbon::parse($workOrder->tanggal)->format('d/m/Y') }}
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <div
                                        class="text-sm text-gray-500 dark:text-gray-400 italic p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                                        Belum ada work order</div>
                                @endif
                            </div>

                            <!-- Delivery Orders -->
                            <div>
                                <h3
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                    </svg>
                                    Delivery Order <span
                                        class="ml-1 text-xs text-gray-400">({{ $salesOrder->deliveryOrders->count() }})</span>
                                </h3>
                                @if ($salesOrder->deliveryOrders && $salesOrder->deliveryOrders->count() > 0)
                                    <div class="space-y-2">
                                        @foreach ($salesOrder->deliveryOrders as $deliveryOrder)
                                            <a href="{{ route('penjualan.delivery-order.show', $deliveryOrder->id) }}"
                                                class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                                <div
                                                    class="flex-shrink-0 w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5 text-green-600 dark:text-green-400"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path
                                                            d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $deliveryOrder->nomor }}</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ \Carbon\Carbon::parse($deliveryOrder->tanggal)->format('d/m/Y') }}
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <div
                                        class="text-sm text-gray-500 dark:text-gray-400 italic p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                                        Belum ada delivery order</div>
                                @endif
                            </div>

                            <!-- Invoices -->
                            <div>
                                <h3
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                    Invoice <span
                                        class="ml-1 text-xs text-gray-400">({{ $salesOrder->invoices->count() }})</span>
                                </h3>
                                @if ($salesOrder->invoices && $salesOrder->invoices->count() > 0)
                                    <div class="space-y-2">
                                        @foreach ($salesOrder->invoices as $invoice)
                                            <a href="{{ route('penjualan.invoice.show', $invoice->id) }}"
                                                class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                                <div
                                                    class="flex-shrink-0 w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5 text-purple-600 dark:text-purple-400"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                                    </svg>
                                                </div>
                                                <div class="ml-3 flex-grow">
                                                    <div class="flex items-center justify-between">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $invoice->nomor }}
                                                        </div>
                                                        <div>
                                                            <span
                                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                                {{ $invoice->status_pembayaran == 'lunas'
                                                                    ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                                                                    : ($invoice->status_pembayaran == 'sebagian'
                                                                        ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400'
                                                                        : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400') }}">
                                                                {{ statusLabel($invoice->status_pembayaran, 'payment') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ \Carbon\Carbon::parse($invoice->tanggal)->format('d/m/Y') }}
                                                        - Rp {{ number_format($invoice->total, 0, ',', '.') }}
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <div
                                        class="text-sm text-gray-500 dark:text-gray-400 italic p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                                        Belum ada invoice</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Log -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 card">
                    <div
                        class="border-b border-gray-200 dark:border-gray-700 px-5 py-4 flex justify-between items-center">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Riwayat Aktivitas</h2>
                        <span
                            class="px-2.5 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-xs">
                            {{ $salesOrder->logAktivitas->count() }} aktivitas
                        </span>
                    </div>
                    <div class="p-5">
                        @if ($salesOrder->logAktivitas && $salesOrder->logAktivitas->count() > 0)
                            <div class="flow-root">
                                <ul class="-mb-8">
                                    @foreach ($salesOrder->logAktivitas->sortByDesc('created_at') as $log)
                                        <li>
                                            <div class="relative pb-8">
                                                @if (!$loop->last)
                                                    <span
                                                        class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700"
                                                        aria-hidden="true"></span>
                                                @endif
                                                <div class="relative flex items-start space-x-3">
                                                    <div>
                                                        <div class="relative px-1">
                                                            <div
                                                                class="h-8 w-8 bg-{{ getActivityColor($log->aktivitas) }}-100 dark:bg-{{ getActivityColor($log->aktivitas) }}-900/30 flex items-center justify-center rounded-full ring-8 ring-white dark:ring-gray-800">
                                                                {!! getActivityIcon($log->aktivitas) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <div>
                                                            <div class="text-sm">
                                                                <a href="#"
                                                                    class="font-medium text-gray-900 dark:text-white">{{ $log->user->name ?? 'Sistem' }}</a>
                                                            </div>
                                                            <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                                                {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                                                                ({{ \Carbon\Carbon::parse($log->created_at)->format('d M Y H:i') }})
                                                            </p>
                                                        </div>
                                                        <div class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                                                            <p>
                                                                {!! formatActivityLog($log) !!}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <div
                                class="text-sm text-gray-500 dark:text-gray-400 italic p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg text-center">
                                Belum ada riwayat aktivitas
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function statusLabel(key, type) {
                if (type === 'payment') {
                    const statuses = {
                        'belum_bayar': 'Belum Bayar',
                        'sebagian': 'Sebagian',
                        'lunas': 'Lunas'
                    };
                    return statuses[key] || key;
                } else {
                    const statuses = {
                        'belum_dikirim': 'Belum Dikirim',
                        'sebagian': 'Sebagian',
                        'dikirim': 'Dikirim'
                    };
                    return statuses[key] || key;
                }
            }

            // Helper function for formatting activity logs
            function formatActivityLog(log) {
                let detail = '';
                try {
                    const detailObj = JSON.parse(log.detail);

                    if (log.aktivitas === 'create') {
                        detail =
                            `Membuat sales order baru dengan nomor <strong>${detailObj.nomor}</strong> untuk customer <strong>${detailObj.customer}</strong> dengan total <strong>Rp ${new Intl.NumberFormat('id-ID').format(detailObj.total)}</strong>`;
                    } else if (log.aktivitas === 'update') {
                        detail =
                            `Mengubah data sales order dari <strong>${detailObj.before.nomor}</strong> menjadi <strong>${detailObj.after.nomor}</strong>`;

                        if (detailObj.before.customer !== detailObj.after.customer) {
                            detail +=
                                `, customer dari <strong>${detailObj.before.customer}</strong> menjadi <strong>${detailObj.after.customer}</strong>`;
                        }

                        if (detailObj.before.total !== detailObj.after.total) {
                            detail +=
                                `, total dari <strong>Rp ${new Intl.NumberFormat('id-ID').format(detailObj.before.total)}</strong> menjadi <strong>Rp ${new Intl.NumberFormat('id-ID').format(detailObj.after.total)}</strong>`;
                        }
                    } else if (log.aktivitas === 'delete') {
                        detail =
                            `Menghapus sales order dengan nomor <strong>${detailObj.nomor}</strong> untuk customer <strong>${detailObj.customer}</strong>`;
                    } else if (log.aktivitas === 'change_status') {
                        let statusChanges = [];

                        if (detailObj.status_pembayaran_lama !== detailObj.status_pembayaran_baru) {
                            statusChanges.push(
                                `status pembayaran dari <strong>${statusLabel(detailObj.status_pembayaran_lama, 'payment')}</strong> menjadi <strong>${statusLabel(detailObj.status_pembayaran_baru, 'payment')}</strong>`
                            );
                        }

                        if (detailObj.status_pengiriman_lama !== detailObj.status_pengiriman_baru) {
                            statusChanges.push(
                                `status pengiriman dari <strong>${statusLabel(detailObj.status_pengiriman_lama, 'delivery')}</strong> menjadi <strong>${statusLabel(detailObj.status_pengiriman_baru, 'delivery')}</strong>`
                            );
                        }

                        detail = `Mengubah ${statusChanges.join(' dan ')} dengan catatan: "${detailObj.catatan || '-'}"`;
                    }
                } catch (e) {
                    detail = log.detail || 'Tidak ada detail';
                }

                return detail;
            }

            function getActivityColor(activity) {
                switch (activity) {
                    case 'create':
                        return 'green';
                    case 'update':
                        return 'blue';
                    case 'delete':
                        return 'red';
                    case 'change_status':
                        return 'amber';
                    default:
                        return 'gray';
                }
            }

            function getActivityIcon(activity) {
                switch (activity) {
                    case 'create':
                        return '<svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" /></svg>';
                    case 'update':
                        return '<svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>';
                    case 'delete':
                        return '<svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>';
                    case 'change_status':
                        return '<svg class="h-5 w-5 text-amber-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>';
                    default:
                        return '<svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" /></svg>';
                }
            }
        </script>
    @endpush
</x-app-layout>
