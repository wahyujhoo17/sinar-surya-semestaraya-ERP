<x-app-layout :breadcrumbs="[]" :currentPage="__('Dashboard Penjualan')">

    @push('styles')
        <style>
            .stat-card {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.1);
            }

            .stat-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08), 0 4px 8px rgba(0, 0, 0, 0.04);
            }

            .conversion-circle {
                width: 120px;
                height: 120px;
                position: relative;
            }
        </style>
    @endpush

    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard Penjualan</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Ringkasan aktivitas penjualan dan performa tim sales</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ now()->format('l, d F Y') }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500">{{ now()->format('H:i') }} WIB</p>
            </div>
        </div>
    </div>

    <!-- Sales Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Quotations This Month -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Quotation Bulan Ini</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($quotationBulanIni) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Sales Orders This Month -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Sales Order Bulan Ini</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($salesOrderBulanIni) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Revenue This Month -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                    <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Penjualan Bulan Ini</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp
                        {{ number_format($penjualanBulanIni, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Conversion Rate -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Conversion Rate</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($conversionRate, 1) }}%
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Orders Status & Top Customers -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Delivery Orders Status -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status Pengiriman</h3>
            <div class="space-y-4">
                @php
                    $statusColors = [
                        'draft' => [
                            'bg' => 'bg-gray-100 dark:bg-gray-700',
                            'text' => 'text-gray-600 dark:text-gray-400',
                            'label' => 'Draft',
                        ],
                        'dikirim' => [
                            'bg' => 'bg-yellow-100 dark:bg-yellow-900',
                            'text' => 'text-yellow-600 dark:text-yellow-400',
                            'label' => 'Dalam Pengiriman',
                        ],
                        'diterima' => [
                            'bg' => 'bg-green-100 dark:bg-green-900',
                            'text' => 'text-green-600 dark:text-green-400',
                            'label' => 'Diterima',
                        ],
                        'dibatalkan' => [
                            'bg' => 'bg-red-100 dark:bg-red-900',
                            'text' => 'text-red-600 dark:text-red-400',
                            'label' => 'Dibatalkan',
                        ],
                    ];
                @endphp

                @foreach ($statusColors as $status => $config)
                    <div class="flex items-center justify-between p-3 rounded-lg {{ $config['bg'] }}">
                        <span class="font-medium {{ $config['text'] }}">{{ $config['label'] }}</span>
                        <span class="text-xl font-bold {{ $config['text'] }}">
                            {{ $deliveryOrders[$status] ?? 0 }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Top Customers -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Pelanggan Bulan Ini</h3>
            <div class="space-y-3">
                @forelse($topCustomers as $index => $customer)
                    <div
                        class="flex items-center justify-between p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                <span
                                    class="text-sm font-bold text-blue-600 dark:text-blue-400">{{ $index + 1 }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $customer->nama }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Total: Rp
                                    {{ number_format($customer->total_pembelian ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">Tidak ada data pelanggan bulan ini</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Quotations -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quotation Terbaru</h3>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @forelse($quotationTerbaru as $quotation)
                    <div
                        class="flex items-center justify-between p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $quotation->nomor }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $quotation->customer->nama ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">
                                {{ $quotation->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="text-right">
                            <span
                                class="px-2 py-1 text-xs font-medium rounded-full 
                                @if ($quotation->status == 'draft') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                @elseif($quotation->status == 'dikirim') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                @elseif($quotation->status == 'disetujui') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                {{ ucfirst($quotation->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">Tidak ada quotation terbaru</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Sales Orders -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Sales Order Terbaru</h3>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @forelse($salesOrderTerbaru as $salesOrder)
                    <div
                        class="flex items-center justify-between p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $salesOrder->nomor }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $salesOrder->customer->nama ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">
                                {{ $salesOrder->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">Rp
                                {{ number_format($salesOrder->total ?? 0, 0, ',', '.') }}</p>
                            <span
                                class="px-2 py-1 text-xs font-medium rounded-full 
                                @if ($salesOrder->status == 'draft') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                @elseif($salesOrder->status == 'confirmed') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                @elseif($salesOrder->status == 'delivered') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                {{ ucfirst($salesOrder->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">Tidak ada sales order terbaru</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6">
        @if (auth()->user()->hasPermission('quotation.create') ||
                auth()->user()->hasPermission('sales_order.create') ||
                auth()->user()->hasPermission('pelanggan.create') ||
                auth()->user()->hasPermission('laporan_penjualan.view'))
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aksi Cepat</h3>
        @endif

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @if (auth()->user()->hasPermission('quotation.create'))
                <a href="{{ route('penjualan.quotation.create') }}"
                    class="flex flex-col items-center p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400 mb-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Buat Quotation</span>
                </a>
            @endif

            @if (auth()->user()->hasPermission('sales_order.create'))
                <a href="{{ route('penjualan.sales-order.create') }}"
                    class="flex flex-col items-center p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400 mb-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Buat Sales Order</span>
                </a>
            @endif

            @if (auth()->user()->hasPermission('pelanggan.create'))
                <a href="{{ route('master-data.pelanggan.create') }}"
                    class="flex flex-col items-center p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400 mb-2" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                        </path>
                    </svg>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Tambah Pelanggan</span>
                </a>
            @endif

            @if (auth()->user()->hasPermission('laporan_penjualan.view'))
                <a href="{{ route('laporan.penjualan.index') }}"
                    class="flex flex-col items-center p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400 mb-2" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Laporan Penjualan</span>
                </a>
            @endif
        </div>
    </div>

</x-app-layout>
