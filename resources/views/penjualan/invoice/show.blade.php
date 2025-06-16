<x-app-layout :breadcrumbs="[
    ['label' => 'Penjualan'],
    ['label' => 'Invoice', 'url' => route('penjualan.invoice.index')],
    ['label' => 'Detail'],
]" :currentPage="'Detail Invoice'">

    @push('styles')
        <style>
            /* Clean card design */
            .card-clean {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                border: 1px solid rgba(229, 231, 235, 0.8);
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
                transition: all 0.3s ease;
            }

            .dark .card-clean {
                background: rgba(31, 41, 55, 0.95);
                border: 1px solid rgba(75, 85, 99, 0.6);
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
            }

            /* Simple hover effect */
            .card-hover:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            }

            .dark .card-hover:hover {
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
            }

            /* Simple button design */
            .btn-simple {
                transition: all 0.2s ease;
                position: relative;
                overflow: hidden;
            }

            .btn-simple:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }

            /* Clean table design */
            .table-clean {
                border-radius: 12px;
                overflow: hidden;
                border: 1px solid rgba(229, 231, 235, 0.8);
            }

            .dark .table-clean {
                border: 1px solid rgba(75, 85, 99, 0.6);
            }

            .table-clean thead {
                background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            }

            .table-clean tbody tr:hover {
                background: rgba(79, 70, 229, 0.04);
                transition: all 0.2s ease;
            }

            .dark .table-clean tbody tr:hover {
                background: rgba(79, 70, 229, 0.1);
            }

            /* Simple status badges */
            .status-simple {
                font-weight: 600;
                font-size: 0.75rem;
                letter-spacing: 0.05em;
                transition: all 0.2s ease;
            }

            /* Financial highlight */
            .financial-text {
                color: #059669;
                font-weight: 700;
                font-variant-numeric: tabular-nums;
            }

            .dark .financial-text {
                color: #10b981;
            }

            /* Icon background */
            .icon-bg {
                background: #4f46e5;
            }

            .icon-bg-success {
                background: #059669;
            }

            .icon-bg-warning {
                background: #d97706;
            }

            .icon-bg-info {
                background: #0284c7;
            }

            /* Simple animations */
            @keyframes gentle-float {

                0%,
                100% {
                    transform: translateY(0px);
                }

                50% {
                    transform: translateY(-4px);
                }
            }

            .float-gentle {
                animation: gentle-float 4s ease-in-out infinite;
            }

            /* Clean scrollbar */
            .scrollbar-clean::-webkit-scrollbar {
                width: 4px;
                height: 4px;
            }

            .scrollbar-clean::-webkit-scrollbar-track {
                background: rgba(156, 163, 175, 0.1);
                border-radius: 2px;
            }

            .scrollbar-clean::-webkit-scrollbar-thumb {
                background: #4f46e5;
                border-radius: 2px;
            }

            .scrollbar-clean::-webkit-scrollbar-thumb:hover {
                background: #4338ca;
            }

            /* Responsive adjustments */
            @media (max-width: 768px) {
                .card-clean {
                    backdrop-filter: blur(4px);
                    -webkit-backdrop-filter: blur(4px);
                }
            }
        </style>
    @endpush

    <!-- Alert Messages -->
    @if (session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-2"
            class="mb-6 card-clean rounded-lg p-4 border-l-4 border-green-500">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div
                        class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                        <svg class="h-4 w-4 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-semibold text-green-800 dark:text-green-200">Berhasil!</h3>
                    <p class="mt-1 text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
                </div>
                <button @click="show = false"
                    class="ml-4 text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-200 transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-2"
            class="mb-6 card-clean rounded-lg p-4 border-l-4 border-red-500">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                        <svg class="h-4 w-4 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-semibold text-red-800 dark:text-red-200">Terjadi Kesalahan!</h3>
                    <p class="mt-1 text-sm text-red-700 dark:text-red-300">{{ session('error') }}</p>
                </div>
                <button @click="show = false"
                    class="ml-4 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200 transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <!-- Header Section -->
    <div class="card-clean card-hover rounded-2xl p-6 mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <!-- Title and Status -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl icon-bg flex items-center justify-center float-gentle">
                        <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">
                            Invoice #{{ $invoice->nomor }}
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1 font-medium">
                            Tanggal: {{ date('d F Y', strtotime($invoice->tanggal)) }}
                        </p>
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="flex items-center">
                    @if ($invoice->status === 'belum_bayar')
                        <span
                            class="status-simple inline-flex items-center px-3 py-1.5 rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                            <span class="w-2 h-2 bg-current rounded-full mr-2"></span>
                            BELUM BAYAR
                        </span>
                    @elseif ($invoice->status === 'sebagian')
                        <span
                            class="status-simple inline-flex items-center px-3 py-1.5 rounded-full bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400">
                            <span class="w-2 h-2 bg-current rounded-full mr-2"></span>
                            BAYAR SEBAGIAN
                        </span>
                    @elseif ($invoice->status === 'lunas')
                        <span
                            class="status-simple inline-flex items-center px-3 py-1.5 rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                            <span class="w-2 h-2 bg-current rounded-full mr-2"></span>
                            LUNAS
                        </span>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('penjualan.invoice.print', $invoice->id) }}" target="_blank"
                    class="btn-simple inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" />
                    </svg>
                    Cetak Invoice
                </a>

                @if ($invoice->status !== 'lunas')
                    <a href="{{ route('penjualan.invoice.edit', $invoice->id) }}"
                        class="btn-simple inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                            class="btn-simple inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-semibold text-white bg-red-600 hover:bg-red-700">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
        <!-- Invoice Information -->
        <div class="card-clean card-hover rounded-xl p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 rounded-lg icon-bg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Informasi Invoice</h2>
            </div>

            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Nomor Invoice</span>
                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $invoice->nomor }}</span>
                </div>
                <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Tanggal</span>
                    <span
                        class="text-sm font-semibold text-gray-900 dark:text-white">{{ date('d/m/Y', strtotime($invoice->tanggal)) }}</span>
                </div>
                <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Jatuh Tempo</span>
                    <span
                        class="text-sm font-semibold text-red-600 dark:text-red-400">{{ date('d/m/Y', strtotime($invoice->jatuh_tempo)) }}</span>
                </div>
                <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Dibuat Oleh</span>
                    <span
                        class="text-sm font-semibold text-gray-900 dark:text-white">{{ $invoice->user->name }}</span>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="card-clean card-hover rounded-xl p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 rounded-lg icon-bg-success flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Informasi Customer</h2>
            </div>

            <div class="space-y-4">

                @if ($invoice->customer->company ?? $invoice->customer->nama)
                    <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Perusahaan</span>
                        <span
                            class="text-sm font-semibold text-gray-900 dark:text-white">{{ $invoice->customer->company ?? $invoice->customer->nama }}</span>
                    </div>
                @endif

                @if ($invoice->customer->email)
                    <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Email</span>
                        <span
                            class="text-sm font-semibold text-gray-900 dark:text-white">{{ $invoice->customer->email }}</span>
                    </div>
                @endif

                @if ($invoice->customer->telepon)
                    <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Telepon</span>
                        <span
                            class="text-sm font-semibold text-gray-900 dark:text-white">{{ $invoice->customer->telepon }}</span>
                    </div>
                @endif
                @if ($invoice->customer->kontak_person)
                    <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Kontak</span>
                        <span
                            class="text-sm font-semibold text-gray-900 dark:text-white">{{ $invoice->customer->kontak_person }}</span>
                    </div>
                @endif
                @if ($invoice->customer->no_hp_kontak)
                    <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">NO HP</span>
                        <span
                            class="text-sm font-semibold text-gray-900 dark:text-white">{{ $invoice->customer->no_hp_kontak }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sales Order Information -->
        <div class="card-clean card-hover rounded-xl p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 rounded-lg icon-bg-info flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Sales Order</h2>
            </div>

            @if ($invoice->salesOrder)
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Nomor Sales Order</span>
                        <a href="{{ route('penjualan.sales-order.show', $invoice->salesOrder->id) }}" target="_blank"
                            class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium hover:underline">
                            {{ $invoice->salesOrder->nomor }}
                            <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                </path>
                            </svg>
                        </a>
                    </div>

                    <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Tanggal SO</span>
                        <span
                            class="text-sm font-semibold text-gray-900 dark:text-white">{{ date('d/m/Y', strtotime($invoice->salesOrder->tanggal)) }}</span>
                    </div>

                    <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Total SO</span>
                        <span class="text-sm font-bold financial-text">Rp
                            {{ number_format($invoice->salesOrder->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">Tidak ada data Sales Order</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Invoice Items -->
    <div class="card-clean card-hover rounded-xl p-6 mb-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-8 h-8 rounded-lg icon-bg-warning flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Detail Item</h2>
        </div>

        <div class="table-clean">
            <div class="overflow-x-auto scrollbar-clean">
                <table class="min-w-full">
                    <thead>
                        <tr class="text-white">
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">No</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Deskripsi
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider">Qty</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Satuan</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider">Diskon</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider">Subtotal
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @if ($invoice->details->isEmpty())
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <p class="text-gray-500 dark:text-gray-400 font-medium">Tidak ada data item</p>
                                    </div>
                                </td>
                            </tr>
                        @else
                            @php $no = 1; @endphp
                            @foreach ($invoice->details as $detail)
                                <tr>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $no++ }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $detail->produk->nama ?? 'Produk tidak ditemukan' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $detail->deskripsi ?? '-' }}</td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900 dark:text-white">
                                        {{ number_format($detail->quantity, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $detail->satuan->nama }}</td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-gray-900 dark:text-white">
                                        Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900 dark:text-white">
                                        {{ $detail->diskon > 0 ? 'Rp ' . number_format($detail->diskon, 0, ',', '.') : '-' }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold financial-text">
                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Totals and Notes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Notes -->
        <div class="card-clean card-hover rounded-xl p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-6 h-6 rounded-lg icon-bg-info flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Catatan</h3>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border-l-4 border-blue-500 min-h-[100px]">
                <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">
                    {{ $invoice->catatan ?? 'Tidak ada catatan' }}
                </p>
            </div>
        </div>

        <!-- Financial Summary -->
        <div class="card-clean card-hover rounded-xl p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-6 h-6 rounded-lg icon-bg-success flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Ringkasan Biaya</h3>
            </div>

            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Subtotal</span>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">Rp
                        {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                </div>

                @if ($invoice->diskon_persen > 0 || $invoice->diskon_nominal > 0)
                    <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <span class="text-sm font-medium text-red-600 dark:text-red-400">
                            Diskon
                            @if ($invoice->diskon_persen > 0)
                                <span
                                    class="ml-1 px-2 py-0.5 text-xs bg-red-200 dark:bg-red-800 rounded-full">{{ number_format($invoice->diskon_persen, 1, ',', '.') }}%</span>
                            @endif
                        </span>
                        <span class="text-sm font-semibold text-red-600 dark:text-red-400">- Rp
                            {{ number_format($invoice->diskon_nominal, 0, ',', '.') }}</span>
                    </div>
                @endif

                <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Ongkos Kirim</span>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">Rp
                        {{ number_format($invoice->ongkos_kirim ?? 0, 0, ',', '.') }}</span>
                </div>

                @if ($invoice->ppn > 0)
                    <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <span class="text-sm font-medium text-blue-600 dark:text-blue-400">
                            PPN
                            <span class="ml-1 px-2 py-0.5 text-xs bg-blue-200 dark:bg-blue-800 rounded-full">11%</span>
                        </span>
                        <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">Rp
                            {{ number_format($invoice->ppn, 0, ',', '.') }}</span>
                    </div>
                @endif

                <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                    <span class="text-base font-bold text-green-800 dark:text-green-200">Total</span>
                    <span class="text-base font-bold financial-text">Rp
                        {{ number_format($invoice->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment History -->
    @if (count($invoice->pembayaranPiutang) > 0)
        <div class="card-clean card-hover rounded-xl p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 rounded-lg icon-bg-success flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Riwayat Pembayaran</h2>
            </div>

            <div class="table-clean">
                <div class="overflow-x-auto scrollbar-clean">
                    <table class="min-w-full">
                        <thead>
                            <tr class="text-white">
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Tanggal
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Metode
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">
                                    Referensi</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Catatan
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider">Jumlah
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @php
                                $no = 1;
                                $totalPembayaran = 0;
                            @endphp
                            @foreach ($invoice->pembayaranPiutang as $pembayaran)
                                @php $totalPembayaran += $pembayaran->jumlah; @endphp
                                <tr>
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
                                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-green-600 dark:text-green-400">
                                        Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach

                            @if ($invoice)
                                <tr class="bg-gray-100 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                                    <td colspan="5"
                                        class="px-6 py-4 text-right text-sm font-bold text-gray-900 dark:text-white">
                                        Total Pembayaran:</td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-green-600 dark:text-green-400">
                                        Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</td>
                                </tr>
                            @endif

                            @if ($invoice->kredit_terapkan > 0)
                                <tr
                                    class="bg-purple-50 dark:bg-purple-900/20 border-t border-purple-200 dark:border-purple-700">
                                    <td colspan="5"
                                        class="px-6 py-4 text-right text-sm font-bold text-purple-800 dark:text-purple-200">
                                        Kredit Terapkan:</td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-purple-600 dark:text-purple-400">
                                        Rp {{ number_format($invoice->kredit_terapkan, 0, ',', '.') }}</td>
                                </tr>
                            @endif

                            <!-- Total Pembayaran -->
                            <tr
                                class="bg-green-50 dark:bg-green-900/20 border-t-2 border-green-200 dark:border-green-700">
                                <td colspan="5"
                                    class="px-6 py-4 text-right text-sm font-bold text-green-800 dark:text-green-200">
                                    Total Pembayaran + Kredit:</td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-green-600 dark:text-green-400">
                                    @php
                                        $totalSeluruhPembayaran = $totalPembayaran + ($invoice->kredit_terapkan ?? 0);
                                    @endphp
                                    Rp {{ number_format($totalSeluruhPembayaran, 0, ',', '.') }}</td>
                            </tr>

                            <!-- Sisa Pembayaran -->
                            <tr class="bg-gray-100 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                                <td colspan="5"
                                    class="px-6 py-4 text-right text-sm font-bold text-gray-900 dark:text-white">Sisa
                                    Pembayaran:</td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-right text-base font-bold {{ $invoice->total - $totalPembayaran > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                    Rp {{ number_format($invoice->sisa_piutang, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

</x-app-layout>
