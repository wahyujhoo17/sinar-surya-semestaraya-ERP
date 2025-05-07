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
        </style>
    @endpush

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

            <div class="flex flex-wrap gap-2 mt-4 md:mt-0" x-data="{ statusDropdownOpen: false, confirmModal: false, newStatus: '', currentStatus: '{{ $purchaseOrder->status }}' }">
                <a href="{{ route('pembelian.purchasing-order.pdf', $purchaseOrder->id) }}" target="_blank"
                    class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak
                </a>

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

                <!-- Professional Status dropdown -->
                <div class="relative" x-cloak>
                    <button @click="statusDropdownOpen = !statusDropdownOpen" type="button"
                        class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
                        <span
                            :class="{
                                'text-gray-700 dark:text-gray-200': currentStatus == 'draft',
                                'text-blue-600 dark:text-blue-400': currentStatus == 'diproses',
                                'text-amber-600 dark:text-amber-400': currentStatus == 'dikirim',
                                'text-emerald-600 dark:text-emerald-400': currentStatus == 'selesai',
                                'text-red-600 dark:text-red-400': currentStatus == 'dibatalkan'
                            }">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 inline" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11" />
                            </svg>
                        </span>
                        Ubah Status
                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div x-show="statusDropdownOpen" @click.away="statusDropdownOpen = false"
                        class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:divide-gray-700 focus:outline-none z-10"
                        role="menu" aria-orientation="vertical" aria-labelledby="options-menu">

                        <div class="py-1" role="none">
                            <span class="block px-4 py-2 text-xs text-gray-500 dark:text-gray-400 uppercase">
                                Status Saat Ini: <span
                                    class="font-semibold capitalize">{{ $purchaseOrder->status }}</span>
                            </span>
                        </div>

                        <div class="py-1" role="none">
                            <!-- Draft status -->
                            <button @click="newStatus = 'draft'; confirmModal = true; statusDropdownOpen = false"
                                class="status-dropdown-item w-full text-left px-4 py-2 text-sm flex items-center transition-all space-x-2 
                                {{ $purchaseOrder->status == 'draft' ? 'bg-gray-100 dark:bg-gray-700 cursor-default' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                                :class="{ 'opacity-50 cursor-not-allowed': currentStatus == 'draft' }" role="menuitem"
                                :disabled="currentStatus == 'draft'">
                                <span class="w-2 h-2 rounded-full bg-gray-500"></span>
                                <span>Draft</span>
                                @if ($purchaseOrder->status == 'draft')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-auto h-4 w-4 text-gray-500"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </button>

                            <!-- Diproses status -->
                            <button @click="newStatus = 'diproses'; confirmModal = true; statusDropdownOpen = false"
                                class="status-dropdown-item w-full text-left px-4 py-2 text-sm flex items-center transition-all space-x-2 
                                {{ $purchaseOrder->status == 'diproses' ? 'bg-blue-50 dark:bg-blue-900/20 cursor-default' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                                :class="{ 'opacity-50 cursor-not-allowed': currentStatus == 'diproses' }"
                                role="menuitem" :disabled="currentStatus == 'diproses'">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                <span class="text-blue-600 dark:text-blue-400">Diproses</span>
                                @if ($purchaseOrder->status == 'diproses')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-auto h-4 w-4 text-blue-500"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </button>

                            <!-- Dikirim status -->
                            <button @click="newStatus = 'dikirim'; confirmModal = true; statusDropdownOpen = false"
                                class="status-dropdown-item w-full text-left px-4 py-2 text-sm flex items-center transition-all space-x-2 
                                {{ $purchaseOrder->status == 'dikirim' ? 'bg-amber-50 dark:bg-amber-900/20 cursor-default' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                                :class="{ 'opacity-50 cursor-not-allowed': currentStatus == 'dikirim' }"
                                role="menuitem" :disabled="currentStatus == 'dikirim'">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                <span class="text-amber-600 dark:text-amber-400">Dikirim</span>
                                @if ($purchaseOrder->status == 'dikirim')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-auto h-4 w-4 text-amber-500"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </button>

                            <!-- Selesai status - with conditional disabling -->
                            <button @click="newStatus = 'selesai'; confirmModal = true; statusDropdownOpen = false"
                                class="status-dropdown-item w-full text-left px-4 py-2 text-sm flex items-center transition-all space-x-2 
                                {{ $purchaseOrder->status == 'selesai' ? 'bg-emerald-50 dark:bg-emerald-900/20 cursor-default' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                                :class="{
                                    'opacity-50 cursor-not-allowed': currentStatus == 'selesai' ||
                                        ('{{ $purchaseOrder->status_pembayaran }}'
                                            !== 'lunas' || '{{ $purchaseOrder->status_penerimaan }}'
                                            !== 'diterima')
                                }"
                                role="menuitem"
                                :disabled="currentStatus == 'selesai' || ('{{ $purchaseOrder->status_pembayaran }}'
                                    !== 'lunas' || '{{ $purchaseOrder->status_penerimaan }}'
                                    !== 'diterima')">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span class="text-emerald-600 dark:text-emerald-400">Selesai</span>
                                <span class="ml-auto flex-shrink-0">
                                    @if ($purchaseOrder->status_pembayaran !== 'lunas' || $purchaseOrder->status_penerimaan !== 'diterima')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-500"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @elseif($purchaseOrder->status == 'selesai')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </span>
                            </button>

                            <!-- Dibatalkan status -->
                            <button @click="newStatus = 'dibatalkan'; confirmModal = true; statusDropdownOpen = false"
                                class="status-dropdown-item w-full text-left px-4 py-2 text-sm flex items-center transition-all space-x-2 
                                {{ $purchaseOrder->status == 'dibatalkan' ? 'bg-red-50 dark:bg-red-900/20 cursor-default' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                                :class="{ 'opacity-50 cursor-not-allowed': currentStatus == 'dibatalkan' }"
                                role="menuitem" :disabled="currentStatus == 'dibatalkan'">
                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                <span class="text-red-600 dark:text-red-400">Dibatalkan</span>
                                @if ($purchaseOrder->status == 'dibatalkan')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-auto h-4 w-4 text-red-500"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Confirmation Modal -->
                <div x-show="confirmModal" class="fixed inset-0 overflow-y-auto z-50" x-cloak>
                    <div
                        class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="confirmModal" @click="confirmModal = false"
                            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            class="fixed inset-0 transition-opacity" aria-hidden="true">
                            <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
                        </div>

                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                            aria-hidden="true">&#8203;</span>

                        <div x-show="confirmModal" x-transition:enter="ease-out duration-300"
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
                                            'bg-gray-100 dark:bg-gray-700': newStatus == 'draft',
                                            'bg-blue-100 dark:bg-blue-900/20': newStatus == 'diproses',
                                            'bg-amber-100 dark:bg-amber-900/20': newStatus == 'dikirim',
                                            'bg-emerald-100 dark:bg-emerald-900/20': newStatus == 'selesai',
                                            'bg-red-100 dark:bg-red-900/20': newStatus == 'dibatalkan',
                                        }">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                            :class="{
                                                'text-gray-600 dark:text-gray-300': newStatus == 'draft',
                                                'text-blue-600 dark:text-blue-400': newStatus == 'diproses',
                                                'text-amber-600 dark:text-amber-400': newStatus == 'dikirim',
                                                'text-emerald-600 dark:text-emerald-400': newStatus == 'selesai',
                                                'text-red-600 dark:text-red-400': newStatus == 'dibatalkan',
                                            }"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                                            Konfirmasi Perubahan Status
                                        </h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                Apakah Anda yakin ingin mengubah status Purchase Order dari <span
                                                    class="font-medium capitalize" x-text="currentStatus"></span>
                                                menjadi <span class="font-medium capitalize"
                                                    x-text="newStatus"></span>?
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2"
                                                x-show="newStatus === 'dibatalkan'">
                                                <span class="text-red-500 font-medium">Perhatian:</span> Membatalkan PO
                                                akan menghentikan semua proses terkait dan tidak dapat dikembalikan.
                                            </p>

                                            <!-- Add new validation warnings -->
                                            <div class="mt-2 p-3 bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-800/30 rounded-md"
                                                x-show="newStatus === 'selesai' && ('{{ $purchaseOrder->status_pembayaran }}' !== 'lunas' || '{{ $purchaseOrder->status_penerimaan }}' !== 'diterima')">
                                                <div class="flex">
                                                    <div class="flex-shrink-0">
                                                        <svg class="h-5 w-5 text-amber-400"
                                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                            fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                    <div class="ml-3">
                                                        <h3
                                                            class="text-sm font-medium text-amber-800 dark:text-amber-300">
                                                            Persyaratan Belum Terpenuhi</h3>
                                                        <div class="mt-2 text-sm text-amber-700 dark:text-amber-300">
                                                            <ul class="list-disc pl-5 space-y-1">
                                                                @if ($purchaseOrder->status_pembayaran !== 'lunas')
                                                                    <li>Pembayaran belum lunas (status:
                                                                        {{ $purchaseOrder->status_pembayaran == 'belum_bayar' ? 'Belum Bayar' : 'Dibayar Sebagian' }})
                                                                    </li>
                                                                @endif

                                                                @if ($purchaseOrder->status_penerimaan !== 'diterima')
                                                                    <li>Barang belum diterima sepenuhnya (status:
                                                                        {{ $purchaseOrder->status_penerimaan == 'belum_diterima' ? 'Belum Diterima' : 'Diterima Sebagian' }})
                                                                    </li>
                                                                @endif
                                                            </ul>
                                                            <p class="mt-1">PO hanya dapat diselesaikan jika
                                                                pembayaran lunas dan semua barang telah diterima.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <form id="changeStatusForm"
                                    action="{{ route('pembelian.purchasing-order.change-status', $purchaseOrder->id) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" x-model="newStatus">
                                    <button type="submit"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white sm:ml-3 sm:w-auto sm:text-sm"
                                        :class="{
                                            'bg-gray-600 hover:bg-gray-700 focus:ring-gray-500': newStatus == 'draft',
                                            'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500': newStatus ==
                                                'diproses',
                                            'bg-amber-600 hover:bg-amber-700 focus:ring-amber-500': newStatus ==
                                                'dikirim',
                                            'bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-500': newStatus ==
                                                'selesai',
                                            'bg-red-600 hover:bg-red-700 focus:ring-red-500': newStatus == 'dibatalkan',
                                        }">
                                        Konfirmasi
                                    </button>
                                </form>
                                <button @click="confirmModal = false" type="button"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Batal
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


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
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Pengiriman
                                </h3>
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
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Progress PO</h3>
                            <div class="relative">
                                <div class="status-timeline flex justify-between items-center mb-6">
                                    <div class="relative z-10 flex flex-col items-center">
                                        <div
                                            class="transition-all duration-300 h-6 w-6 flex items-center justify-center rounded-full border-2 
                                            {{ $purchaseOrder->status == 'draft' ? 'bg-blue-500 border-blue-500 shadow-md shadow-blue-200 dark:shadow-blue-900/20' : ($purchaseOrder->status == 'dibatalkan' ? 'bg-gray-200 border-gray-300 dark:bg-gray-700 dark:border-gray-600' : 'bg-blue-500 border-blue-500') }}">
                                            @if ($purchaseOrder->status == 'draft')
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
                                            {{ $purchaseOrder->status == 'draft' ? 'text-blue-600 dark:text-blue-400' : ($purchaseOrder->status == 'dibatalkan' ? 'text-gray-500 dark:text-gray-400' : 'text-blue-600 dark:text-blue-400') }}">
                                            Draft
                                        </span>
                                    </div>
                                    <div
                                        class="w-full h-0.5 
                                        {{ in_array($purchaseOrder->status, ['diproses', 'dikirim', 'selesai']) ? 'bg-blue-500' : 'bg-gray-200 dark:bg-gray-700' }}">
                                    </div>

                                    <div class="relative z-10 flex flex-col items-center">
                                        <div
                                            class="transition-all duration-300 h-6 w-6 flex items-center justify-center rounded-full border-2 
                                            {{ $purchaseOrder->status == 'diproses' ? 'bg-blue-500 border-blue-500 shadow-md shadow-blue-200 dark:shadow-blue-900/20' : (in_array($purchaseOrder->status, ['dikirim', 'selesai']) ? 'bg-blue-500 border-blue-500' : 'bg-gray-200 border-gray-300 dark:bg-gray-700 dark:border-gray-600') }}">
                                            @if ($purchaseOrder->status == 'diproses')
                                                <svg class="h-3 w-3 text-white" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414 0l-3-3a1 1 0 001.414 1.414L9 11.414V14a1 1 0 102 0v-2.586l1.293 1.293a1 1 0 001.414-1.414l-3-3z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            @elseif(in_array($purchaseOrder->status, ['dikirim', 'selesai']))
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
                                            {{ $purchaseOrder->status == 'diproses' ? 'text-blue-600 dark:text-blue-400' : (in_array($purchaseOrder->status, ['dikirim', 'selesai']) ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400') }}">
                                            Diproses
                                        </span>
                                    </div>
                                    <div
                                        class="w-full h-0.5 
                                        {{ in_array($purchaseOrder->status, ['dikirim', 'selesai']) ? 'bg-blue-500' : 'bg-gray-200 dark:bg-gray-700' }}">
                                    </div>

                                    <div class="relative z-10 flex flex-col items-center">
                                        <div
                                            class="transition-all duration-300 h-6 w-6 flex items-center justify-center rounded-full border-2 
                                            {{ $purchaseOrder->status == 'dikirim' ? 'bg-blue-500 border-blue-500 shadow-md shadow-blue-200 dark:shadow-blue-900/20' : ($purchaseOrder->status == 'selesai' ? 'bg-blue-500 border-blue-500' : 'bg-gray-200 border-gray-300 dark:bg-gray-700 dark:border-gray-600') }}">
                                            @if ($purchaseOrder->status == 'dikirim')
                                                <svg class="h-3 w-3 text-white" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414 0l-3-3a1 1 0 001.414 1.414L9 11.414V14a1 1 0 102 0v-2.586l1.293 1.293a1 1 0 001.414-1.414l-3-3z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            @elseif($purchaseOrder->status == 'selesai')
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
                                            {{ $purchaseOrder->status == 'dikirim' ? 'text-blue-600 dark:text-blue-400' : ($purchaseOrder->status == 'selesai' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400') }}">
                                            Dikirim
                                        </span>
                                    </div>
                                    <div
                                        class="w-full h-0.5 
                                        {{ $purchaseOrder->status == 'selesai' ? 'bg-blue-500' : 'bg-gray-200 dark:bg-gray-700' }}">
                                    </div>

                                    <div class="relative z-10 flex flex-col items-center">
                                        <div
                                            class="transition-all duration-300 h-6 w-6 flex items-center justify-center rounded-full border-2 
                                            {{ $purchaseOrder->status == 'selesai' ? 'bg-emerald-500 border-emerald-500 shadow-md shadow-emerald-200 dark:shadow-emerald-900/20' : 'bg-gray-200 border-gray-300 dark:bg-gray-700 dark:border-gray-600' }}">
                                            @if ($purchaseOrder->status == 'selesai')
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
                                            {{ $purchaseOrder->status == 'selesai' ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-500 dark:text-gray-400' }}">
                                            Selesai
                                        </span>
                                    </div>
                                </div>

                                @if ($purchaseOrder->status == 'dibatalkan')
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
                                                    Purchase Order telah dibatalkan
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Payment & Receipt Status -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Payment Status -->
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Status Pembayaran
                                </h3>
                                <div>
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
                                    <div class="relative pt-1">
                                        <div class="flex mb-1 items-center justify-between">
                                            <div>
                                                <span
                                                    class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full {{ $paymentStatusColor }}">
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
                                        <div
                                            class="overflow-hidden h-2 mb-1 text-xs flex rounded-full bg-gray-200 dark:bg-gray-700">
                                            <div style="width:{{ $purchaseOrder->status_pembayaran == 'belum_bayar' ? '0%' : ($purchaseOrder->status_pembayaran == 'sebagian' ? '50%' : '100%') }}"
                                                class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center {{ $paymentStatusBarColor }} transition-all duration-500">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Receipt Status -->
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Status Penerimaan
                                </h3>
                                <div>
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

                                        $receiptStatusBarColor = match ($purchaseOrder->status_penerimaan) {
                                            'belum_diterima' => 'bg-red-500 dark:bg-red-600',
                                            'sebagian' => 'bg-yellow-500 dark:bg-yellow-600',
                                            'diterima' => 'bg-emerald-500 dark:bg-emerald-600',
                                            default => 'bg-gray-500 dark:bg-gray-600',
                                        };
                                    @endphp
                                    <div class="relative pt-1">
                                        <div class="flex mb-1 items-center justify-between">
                                            <div>
                                                <span
                                                    class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full {{ $receiptStatusColor }}">
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
                                        <div
                                            class="overflow-hidden h-2 mb-1 text-xs flex rounded-full bg-gray-200 dark:bg-gray-700">
                                            <div style="width:{{ $purchaseOrder->status_penerimaan == 'belum_diterima' ? '0%' : ($purchaseOrder->status_penerimaan == 'sebagian' ? '50%' : '100%') }}"
                                                class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center {{ $receiptStatusBarColor }} transition-all duration-500">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2 flex flex-col gap-2">
                            <a href="#"
                                class="status-transition w-full inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 hover:border-primary-500 dark:hover:border-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-primary-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Catat Penerimaan Barang
                            </a>

                            <a href="#"
                                class="status-transition w-full inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 hover:border-primary-500 dark:hover:border-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-primary-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                    <div class="relative">
                        <div class="status-history-line"></div>
                        <div class="space-y-6">
                            <div class="relative flex gap-4">
                                <div class="flex h-6 items-center">
                                    <div
                                        class="z-10 flex h-6 w-6 items-center justify-center rounded-full bg-primary-100 text-primary-600 ring-0 ring-white dark:bg-primary-900/20 dark:text-primary-400 dark:ring-gray-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between gap-2 items-center mb-1">
                                        <div>
                                            <span class="font-medium text-sm text-gray-900 dark:text-white">PO
                                                Dibuat</span>
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($purchaseOrder->created_at)->format('d M Y, H:i') }}
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $purchaseOrder->user->name ?? 'Unknown' }} membuat purchase order
                                    </p>
                                </div>
                            </div>

                            @if ($purchaseOrder->created_at != $purchaseOrder->updated_at)
                                <div class="relative flex gap-4">
                                    <div class="flex h-6 items-center">
                                        <div
                                            class="z-10 flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-blue-600 ring-0 ring-white dark:bg-blue-900/20 dark:text-blue-400 dark:ring-gray-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between gap-2 items-center mb-1">
                                            <div>
                                                <span class="font-medium text-sm text-gray-900 dark:text-white">PO
                                                    Diperbarui</span>
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($purchaseOrder->updated_at)->format('d M Y, H:i') }}
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            Purchase order diperbarui
                                        </p>
                                    </div>
                                </div>
                            @endif

                            <!-- Status history would appear here in a production app -->
                        </div>
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

            // Add Alpine.js for interactivity if not already included
            document.addEventListener('alpine:init', () => {
                Alpine.data('statusControl', () => ({
                    confirmModal: false,
                    newStatus: '',
                    currentStatus: '{{ $purchaseOrder->status }}',

                    openConfirmation(status) {
                        this.newStatus = status;
                        this.confirmModal = true;
                    },

                    getStatusColor(status) {
                        return poStatusColor(status);
                    }
                }))
            });
        </script>
    @endpush
</x-app-layout>
