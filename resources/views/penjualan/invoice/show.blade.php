<x-app-layout :breadcrumbs="[
    ['label' => 'Penjualan'],
    ['label' => 'Invoice', 'url' => route('penjualan.invoice.index')],
    ['label' => 'Detail'],
]" :currentPage="'Detail Invoice'">

    @push('styles')
        <style>
            /* Table row hover */
            .table-row:hover {
                background-color: rgba(243, 244, 246, 0.7);
                transition: all 0.3s ease;
            }

            .dark .table-row:hover {
                background-color: rgba(55, 65, 81, 0.7);
                transition: all 0.3s ease;
            }

            /* Table styling improvements */
            .table-container {
                overflow-x: auto;
                border-radius: 0.5rem;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            }

            .table-header {
                position: sticky;
                top: 0;
                z-index: 10;
            }

            /* Card hover effects - removed excessive effects */
            .card-effect {
                border: 1px solid transparent;
            }

            /* Status badge pulse animation */
            @keyframes pulse {

                0%,
                100% {
                    opacity: 1;
                }

                50% {
                    opacity: 0.7;
                }
            }

            .animate-pulse-slow {
                animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }

            /* Data value styling */
            .data-value {
                background-color: rgba(243, 244, 246, 0.5);
                border-radius: 0.375rem;
                padding: 0.25rem 0.5rem;
            }

            .dark .data-value {
                background-color: rgba(31, 41, 55, 0.5);
            }

            /* Section containers */
            .section-container {
                max-width: 100%;
                margin: 0 auto;
                padding: 1rem;
                border-radius: 0.5rem;
            }

            /* Responsive layout improvements */
            @media (max-width: 768px) {
                .section-container {
                    padding: 0.75rem;
                }

                .data-value {
                    font-size: 0.75rem;
                }
            }

            /* Focus styles for financial values */
            .financial-value {
                font-feature-settings .glass-effect {
                    background: rgba(255, 255, 255, 0.25);
                    backdrop-filter: blur(4px);
                    -webkit-backdrop-filter: blur(4px);
                }

                .dark .glass-effect {
                    background: rgba(17, 24, 39, 0.75);
                }

                /* Print button effect */
                .print-button {
                    position: relative;
                    overflow: hidden;
                }

                .print-button:after {
                    content: '';
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    width: 5px;
                    height: 5px;
                    background: rgba(255, 255, 255, 0.3);
                    opacity: 0;
                    border-radius: 100%;
                    transform: scale(1, 1) translate(-50%);
                    transform-origin: 50% 50%;
                }

                .print-button:hover:after {
                    animation: ripple 1s ease-out;
                }

                @keyframes ripple {
                    0% {
                        transform: scale(0, 0);
                        opacity: 0.5;
                    }

                    100% {
                        transform: scale(20, 20);
                        opacity: 0;
                    }
                }

                /* Data value styling */
                .data-value {
                    background-color: rgba(243, 244, 246, 0.5);
                    border-radius: 0.375rem;
                    padding: 0.375rem 0.625rem;
                    transition: all 0.2s ease;
                }

                .dark .data-value {
                    background-color: rgba(31, 41, 55, 0.5);
                }

                .data-value:hover {
                    background-color: rgba(243, 244, 246, 0.8);
                }

                .dark .data-value:hover {
                    background-color: rgba(31, 41, 55, 0.8);
                }

                /* Section containers */
                .section-container {
                    max-width: 100%;
                    margin: 0 auto;
                    padding: 1.5rem;
                    border-radius: 0.75rem;
                    transition: all 0.3s ease;
                }

                /* Responsive layout improvements */
                @media (max-width: 768px) {
                    .section-container {
                        padding: 1rem;
                    }

                    .data-value {
                        font-size: 0.875rem;
                    }
                }

                /* Balanced columns */
                @media (min-width: 1024px) {
                    .balanced-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                        gap: 1.5rem;
                    }
                }

                /* Shadow improvements */
                .enhanced-shadow {
                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                }

                .dark .enhanced-shadow {
                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2), 0 4px 6px -2px rgba(0, 0, 0, 0.1);
                }

                /* Focus styles for financial values */
                .financial-value {
                    font-feature-settings: "tnum";
                    font-variant-numeric: tabular-nums;
                    letter-spacing: -0.01em;
                }
        </style>
    @endpush

    <!-- Success Alert -->
    @if (session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
            class="mb-6 bg-green-50 border border-green-200 dark:bg-green-900/20 dark:border-green-700 rounded-lg shadow-sm p-4"
            role="alert">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-600 dark:text-green-400">Berhasil!</h3>
                    <div class="mt-1 text-sm text-green-600 dark:text-green-400">
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button type="button" @click="show = false"
                            class="inline-flex rounded-md p-1.5 text-green-600 hover:text-green-700 hover:bg-green-100 focus:outline-none focus:bg-green-100 focus:text-green-700 dark:text-green-400 dark:hover:bg-green-900 dark:hover:text-green-300 dark:focus:bg-green-900 dark:focus:text-green-300 transition ease-in-out duration-150">
                            <span class="sr-only">Dismiss</span>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Error Alert -->
    @if (session('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
            class="mb-6 bg-red-50 border border-red-200 dark:bg-red-900/20 dark:border-red-700 rounded-lg shadow-sm p-4"
            role="alert">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-600 dark:text-red-400">Terjadi Kesalahan!</h3>
                    <div class="mt-1 text-sm text-red-600 dark:text-red-400">
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button type="button" @click="show = false"
                            class="inline-flex rounded-md p-1.5 text-red-600 hover:text-red-700 hover:bg-red-100 focus:outline-none focus:bg-red-100 focus:text-red-700 dark:text-red-400 dark:hover:bg-red-900 dark:hover:text-red-300 dark:focus:bg-red-900 dark:focus:text-red-300 transition ease-in-out duration-150">
                            <span class="sr-only">Dismiss</span>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
            <div class="flex flex-col md:flex-row md:items-center gap-3">
                <h1 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                    <span class="bg-gray-100 dark:bg-gray-700 text-primary-600 dark:text-primary-400 p-2 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </span>
                    <span class="text-gray-900 dark:text-white">
                        Detail Invoice #{{ $invoice->nomor }}
                    </span>
                </h1>

                @if ($invoice->status === 'belum_bayar')
                    <span
                        class="inline-flex items-center px-3.5 py-1.5 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-400 shadow-sm status-badge status-badge-red animate-pulse-slow">
                        <svg class="-ml-0.5 mr-1.5 h-3 w-3 text-red-600 dark:text-red-400" fill="currentColor"
                            viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3" />
                        </svg>
                        Belum Bayar
                    </span>
                @elseif ($invoice->status === 'sebagian')
                    <span
                        class="inline-flex items-center px-3.5 py-1.5 rounded-full text-sm font-medium bg-orange-100 text-orange-800 dark:bg-orange-800/30 dark:text-orange-400 shadow-sm status-badge status-badge-yellow">
                        <svg class="-ml-0.5 mr-1.5 h-3 w-3 text-orange-600 dark:text-orange-400" fill="currentColor"
                            viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3" />
                        </svg>
                        Bayar Sebagian
                    </span>
                @elseif ($invoice->status === 'lunas')
                    <span
                        class="inline-flex items-center px-3.5 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-400 shadow-sm status-badge status-badge-green">
                        <svg class="-ml-0.5 mr-1.5 h-3 w-3 text-green-600 dark:text-green-400" fill="currentColor"
                            viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3" />
                        </svg>
                        Lunas
                    </span>
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('penjualan.invoice.print', $invoice->id) }}" target="_blank"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 print-button">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" />
                    </svg>
                    Cetak Invoice
                </a>

                @if ($invoice->status !== 'lunas')
                    <a href="{{ route('penjualan.invoice.edit', $invoice->id) }}"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Invoice
                    </a>

                    <form action="{{ route('penjualan.invoice.destroy', $invoice->id) }}" method="POST"
                        onsubmit="return confirm('Anda yakin ingin menghapus invoice ini?');" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-md border border-gray-200 dark:border-gray-700">
            <div class="relative p-5">
                <div class="absolute top-0 right-0 h-20 w-20 opacity-5 dark:opacity-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full text-primary-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <!-- Invoice Details -->
                    <div class="lg:col-span-1">
                        <h2 class="text-base font-medium text-gray-900 dark:text-white mb-2 flex items-center">
                            <span
                                class="bg-gray-100 dark:bg-gray-700 text-primary-600 dark:text-primary-400 p-1.5 rounded-md mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </span>
                            Informasi Invoice
                        </h2>
                        <div
                            class="bg-white dark:bg-gray-800 rounded-md overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700">
                            <dl class="divide-y divide-gray-100 dark:divide-gray-700">
                                <div
                                    class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-3 sm:px-4 hover:bg-gray-50/70 dark:hover:bg-gray-700/40 transition-colors duration-150">
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Nomor Invoice</dt>
                                    <dd
                                        class="mt-1 text-sm font-medium text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                                        {{ $invoice->nomor }}
                                    </dd>
                                </div>
                                <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-5">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</dt>
                                    <dd
                                        class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2 py-1 px-2 bg-gray-50 dark:bg-gray-800/50 rounded-md">
                                        <span class="inline-flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 mr-1.5 text-gray-500" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ date('d/m/Y', strtotime($invoice->tanggal)) }}
                                        </span>
                                    </dd>
                                </div>
                                <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-5">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jatuh Tempo</dt>
                                    <dd
                                        class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2 py-1 px-2 bg-gray-50 dark:bg-gray-800/50 rounded-md">
                                        <span class="inline-flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 mr-1.5 text-red-500" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ date('d/m/Y', strtotime($invoice->jatuh_tempo)) }}
                                        </span>
                                    </dd>
                                </div>
                                <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-5">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                                        @if ($invoice->status === 'belum_bayar')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-400">
                                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-red-600 dark:text-red-400"
                                                    fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                Belum Bayar
                                            </span>
                                        @elseif ($invoice->status === 'sebagian')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-800/30 dark:text-orange-400">
                                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-orange-600 dark:text-orange-400"
                                                    fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                Bayar Sebagian
                                            </span>
                                        @elseif ($invoice->status === 'lunas')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-400">
                                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-600 dark:text-green-400"
                                                    fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                Lunas
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-5">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat Oleh</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                                        {{ $invoice->user->name }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Customer Details -->
                    <div class="lg:col-span-1">
                        <h2 class="text-base font-medium text-gray-900 dark:text-white mb-2 flex items-center">
                            <span
                                class="bg-gray-100 dark:bg-gray-700 text-blue-600 dark:text-blue-400 p-1.5 rounded-md mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                            Informasi Customer
                        </h2>
                        <div
                            class="bg-white dark:bg-gray-800 rounded-md overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700">
                            <dl class="divide-y divide-gray-100 dark:divide-gray-700">
                                <div
                                    class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-3 sm:px-4 hover:bg-gray-50/70 dark:hover:bg-gray-700/40 transition-colors duration-150">
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Nama</dt>
                                    <dd
                                        class="mt-1 text-sm font-medium text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                                        {{ $invoice->customer->nama }}
                                    </dd>
                                </div>
                                <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-5">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Perusahaan</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                                        {{ $invoice->customer->company ?? '-' }}
                                    </dd>
                                </div>
                                <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-5">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                                        {{ $invoice->customer->email ?? '-' }}
                                    </dd>
                                </div>
                                <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-5">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Telepon</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                                        {{ $invoice->customer->telepon ?? '-' }}
                                    </dd>
                                </div>
                                <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-5">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                                        {{ $invoice->customer->alamat ?? '-' }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Sales Order Details -->
                    <div class="lg:col-span-1">
                        <h2 class="text-base font-medium text-gray-900 dark:text-white mb-2 flex items-center">
                            <span
                                class="bg-gray-100 dark:bg-gray-700 text-green-600 dark:text-green-400 p-1.5 rounded-md mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </span>
                            Informasi Sales Order
                        </h2>
                        <div
                            class="bg-white dark:bg-gray-800 rounded-md overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700">
                            <dl class="divide-y divide-gray-100 dark:divide-gray-700">
                                @if ($invoice->salesOrder)
                                    <div
                                        class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-3 sm:px-4 hover:bg-gray-50/70 dark:hover:bg-gray-700/40 transition-colors duration-150">
                                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Nomor SO</dt>
                                        <dd
                                            class="mt-1 text-sm font-medium text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                                            <a href="{{ route('penjualan.sales-order.show', $invoice->salesOrder->id) }}"
                                                class="text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 hover:underline">
                                                {{ $invoice->salesOrder->nomor }}
                                            </a>
                                        </dd>
                                    </div>
                                    <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-5">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal SO
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                                            {{ date('d/m/Y', strtotime($invoice->salesOrder->tanggal)) }}
                                        </dd>
                                    </div>
                                    <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-5">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status
                                            Pembayaran
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                                            @if ($invoice->salesOrder->status_pembayaran === 'belum_bayar')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-400 shadow-sm status-badge status-badge-red">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="-ml-0.5 mr-1.5 h-3 w-3 text-red-600 dark:text-red-400"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Belum Bayar
                                                </span>
                                            @elseif($invoice->salesOrder->status_pembayaran === 'sebagian')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-800/30 dark:text-yellow-400 shadow-sm status-badge status-badge-yellow">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="-ml-0.5 mr-1.5 h-3 w-3 text-yellow-600 dark:text-yellow-400"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Bayar Sebagian
                                                </span>
                                            @elseif($invoice->salesOrder->status_pembayaran === 'lunas')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-400 shadow-sm status-badge status-badge-green">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="-ml-0.5 mr-1.5 h-3 w-3 text-green-600 dark:text-green-400"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Lunas
                                                </span>
                                            @endif
                                        </dd>
                                    </div>
                                    <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-5">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status
                                            Pengiriman
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                                            @if ($invoice->salesOrder->status_pengiriman === 'belum_dikirim')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-400 shadow-sm status-badge status-badge-red">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="-ml-0.5 mr-1.5 h-3 w-3 text-red-600 dark:text-red-400"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                    </svg>
                                                    Belum Dikirim
                                                </span>
                                            @elseif($invoice->salesOrder->status_pengiriman === 'sebagian')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-800/30 dark:text-yellow-400 shadow-sm status-badge status-badge-yellow">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="-ml-0.5 mr-1.5 h-3 w-3 text-yellow-600 dark:text-yellow-400"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path
                                                            d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                                    </svg>
                                                    Dikirim Sebagian
                                                </span>
                                            @elseif($invoice->salesOrder->status_pengiriman === 'dikirim')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-400 shadow-sm status-badge status-badge-green">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="-ml-0.5 mr-1.5 h-3 w-3 text-green-600 dark:text-green-400"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path
                                                            d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                                    </svg>
                                                    Dikirim
                                                </span>
                                            @endif
                                        </dd>
                                    </div>
                                    <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-5">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total SO</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                                            Rp {{ number_format($invoice->salesOrder->total, 0, ',', '.') }}
                                        </dd>
                                    </div>
                                @else
                                    <div class="px-4 py-5 text-center">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Tidak ada data Sales
                                            Order</span>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Invoice Items -->
                <div class="mt-6">
                    <h2 class="text-base font-medium text-gray-900 dark:text-white mb-2 flex items-center">
                        <span
                            class="bg-gray-100 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 p-1.5 rounded-md mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </span>
                        Detail Item
                    </h2>
                    <div
                        class="table-container overflow-x-auto rounded-md border border-gray-200 dark:border-gray-700 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700 table-header">
                                <tr>
                                    <th scope="col"
                                        class="sticky top-0 px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        No
                                    </th>
                                    <th scope="col"
                                        class="sticky top-0 px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Produk
                                    </th>
                                    <th scope="col"
                                        class="sticky top-0 px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Deskripsi
                                    </th>
                                    <th scope="col"
                                        class="sticky top-0 px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Kuantitas
                                    </th>
                                    <th scope="col"
                                        class="sticky top-0 px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Satuan
                                    </th>
                                    <th scope="col"
                                        class="sticky top-0 px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Harga
                                    </th>
                                    <th scope="col"
                                        class="sticky top-0 px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Diskon
                                    </th>
                                    <th scope="col"
                                        class="sticky top-0 px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Subtotal
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @if ($invoice->details->isEmpty())
                                    <tr>
                                        <td colspan="8"
                                            class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            Tidak ada data item
                                        </td>
                                    </tr>
                                @else
                                    @php $no = 1; @endphp
                                    @foreach ($invoice->details as $detail)
                                        <tr
                                            class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                            <td
                                                class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $no++ }}
                                            </td>
                                            <td
                                                class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $detail->produk->nama ?? 'Produk tidak ditemukan' }}
                                            </td>
                                            <td
                                                class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $detail->deskripsi ?? '-' }}
                                            </td>
                                            <td
                                                class="px-4 py-2 whitespace-nowrap text-right text-sm text-gray-900 dark:text-white">
                                                {{ number_format($detail->quantity, 2, ',', '.') }}
                                            </td>
                                            <td
                                                class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $detail->satuan->nama }}
                                            </td>
                                            <td
                                                class="px-4 py-2 whitespace-nowrap text-right text-sm text-gray-900 dark:text-white">
                                                Rp {{ number_format($detail->harga, 0, ',', '.') }}
                                            </td>
                                            <td
                                                class="px-4 py-2 whitespace-nowrap text-right text-sm text-gray-900 dark:text-white">
                                                {{ $detail->diskon > 0 ? 'Rp ' . number_format($detail->diskon, 0, ',', '.') : '-' }}
                                            </td>
                                            <td
                                                class="px-4 py-2 whitespace-nowrap text-right text-sm font-semibold text-gray-900 dark:text-white">
                                                Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Totals -->
                <div class="mt-6">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-md p-4 border border-gray-200 dark:border-gray-700 shadow-sm">
                        <div class="flex flex-col md:flex-row md:justify-between gap-6">
                            <!-- Invoice Notes -->
                            <div class="md:w-3/5">
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2 flex items-center">
                                    <span class="bg-gray-100 dark:bg-gray-700 p-1 rounded-md mr-2">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-3.5 w-3.5 text-gray-600 dark:text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </span>
                                    Catatan
                                </h3>
                                <div
                                    class="mt-1 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-md text-gray-600 dark:text-gray-300 text-xs h-full min-h-[60px] max-h-[100px] overflow-y-auto border border-gray-200 dark:border-gray-700">
                                    {{ $invoice->catatan ?? 'Tidak ada catatan' }}
                                </div>
                            </div>

                            <!-- Invoice Totals -->
                            <div class="md:w-2/5">
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2 flex items-center">
                                    <span class="bg-gray-100 dark:bg-gray-700 p-1 rounded-md mr-2">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-3.5 w-3.5 text-gray-600 dark:text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                        </svg>
                                    </span>
                                    Ringkasan Biaya
                                </h3>
                                <div
                                    class="bg-gray-50 dark:bg-gray-800/50 rounded-md p-3 border border-gray-200 dark:border-gray-700">
                                    <dl class="-my-1 text-xs space-y-2 divide-y divide-gray-100 dark:divide-gray-700">
                                        <div class="py-1.5 flex justify-between">
                                            <dt class="text-gray-600 dark:text-gray-400">Subtotal</dt>
                                            <dd class="font-medium text-gray-900 dark:text-white text-right">
                                                Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}
                                            </dd>
                                        </div>
                                        @if ($invoice->diskon_persen > 0 || $invoice->diskon_nominal > 0)
                                            <div class="py-1.5 flex justify-between">
                                                <dt class="text-gray-600 dark:text-gray-400">
                                                    Diskon
                                                    @if ($invoice->diskon_persen > 0)
                                                        <span
                                                            class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-50 text-red-800 ml-1">
                                                            {{ number_format($invoice->diskon_persen, 1, ',', '.') }}%
                                                        </span>
                                                    @endif
                                                </dt>
                                                <dd class="font-medium text-red-600 dark:text-red-400 text-right">
                                                    - Rp {{ number_format($invoice->diskon_nominal, 0, ',', '.') }}
                                                </dd>
                                            </div>
                                        @endif
                                        <div class="py-1.5 flex justify-between">
                                            <dt class="text-gray-600 dark:text-gray-400">Ongkos Kirim</dt>
                                            <dd class="font-medium text-gray-900 dark:text-white text-right">
                                                Rp {{ number_format($invoice->ongkos_kirim ?? 0, 0, ',', '.') }}
                                            </dd>
                                        </div>
                                        @if ($invoice->ppn > 0)
                                            <div class="py-1.5 flex justify-between">
                                                <dt class="text-gray-600 dark:text-gray-400">
                                                    PPN
                                                    <span
                                                        class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-800 ml-1">
                                                        11%
                                                    </span>
                                                </dt>
                                                <dd class="font-medium text-gray-900 dark:text-white text-right">
                                                    Rp {{ number_format($invoice->ppn, 0, ',', '.') }}
                                                </dd>
                                            </div>
                                        @endif
                                        <div class="py-2 flex justify-between">
                                            <dt class="text-gray-900 dark:text-white font-bold text-sm md:text-base">Total</dt>
                                            <dd class="font-bold text-gray-900 dark:text-white text-right text-sm md:text-base">
                                                Rp {{ number_format($invoice->total, 0, ',', '.') }}
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payments Section -->
                @if (count($invoice->pembayaran) > 0)
                    <div class="mt-6">
                        <h2 class="text-base font-medium text-gray-900 dark:text-white mb-2 flex items-center">
                            <span
                                class="bg-gray-100 dark:bg-gray-700 text-emerald-600 dark:text-emerald-400 p-1.5 rounded-md mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                </svg>
                            </span>
                            Riwayat Pembayaran
                        </h2>
                        <div
                            class="table-container overflow-x-auto rounded-md border border-gray-200 dark:border-gray-700 shadow-sm bg-white dark:bg-gray-800">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700 table-header">
                                    <tr>
                                        <th scope="col"
                                            class="sticky top-0 px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            No
                                        </th>
                                        <th scope="col"
                                            class="sticky top-0 px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Tanggal
                                        </th>
                                        <th scope="col"
                                            class="sticky top-0 px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Metode Pembayaran
                                        </th>
                                        <th scope="col"
                                            class="sticky top-0 px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Referensi
                                        </th>
                                        <th scope="col"
                                            class="sticky top-0 px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Catatan
                                        </th>
                                        <th scope="col"
                                            class="sticky top-0 px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Jumlah
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @php
                                        $no = 1;
                                        $totalPembayaran = 0;
                                    @endphp
                                    @foreach ($invoice->pembayaran as $pembayaran)
                                        @php $totalPembayaran += $pembayaran->jumlah; @endphp
                                        <tr
                                            class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                            <td
                                                class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $no++ }}
                                            </td>
                                            <td
                                                class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                <span class="inline-flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-3.5 w-3.5 mr-1 text-gray-500" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ date('d/m/Y', strtotime($pembayaran->tanggal)) }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                <span
                                                    class="px-2 py-0.5 bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 rounded text-xs">
                                                    {{ $pembayaran->metode_pembayaran }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $pembayaran->referensi ?? '-' }}
                                            </td>
                                            <td
                                                class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $pembayaran->catatan ?? '-' }}
                                            </td>
                                            <td
                                                class="px-4 py-2 whitespace-nowrap text-right text-sm font-medium text-green-600 dark:text-green-400">
                                                Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="bg-gray-50 dark:bg-gray-700/50">
                                        <td colspan="5"
                                            class="px-4 py-2 whitespace-nowrap text-right text-sm font-medium text-gray-900 dark:text-white border-t border-gray-200 dark:border-gray-600">
                                            Total Pembayaran:
                                        </td>
                                        <td
                                            class="px-4 py-2 whitespace-nowrap text-right text-sm font-medium text-green-600 dark:text-green-400 border-t border-gray-200 dark:border-gray-600">
                                            Rp {{ number_format($totalPembayaran, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr class="bg-gray-100 dark:bg-gray-700">
                                        <td colspan="5"
                                            class="px-4 py-2 whitespace-nowrap text-right text-sm font-medium text-gray-900 dark:text-white">
                                            Sisa Pembayaran:
                                        </td>
                                        <td
                                            class="px-4 py-2 whitespace-nowrap text-right text-sm font-medium {{ $invoice->total - $totalPembayaran > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                            Rp {{ number_format($invoice->total - $totalPembayaran, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
