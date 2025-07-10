<x-app-layout :breadcrumbs="[]" :currentPage="__('Dashboard Pembelian')">

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
        </style>
    @endpush

    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard Pembelian</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Monitoring purchase order dan supplier management</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ now()->format('l, d F Y') }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500">{{ now()->format('H:i') }} WIB</p>
            </div>
        </div>
    </div>

    <!-- Purchase Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- PR Pending -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                    <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">PR Pending</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($prPending) }}</p>
                </div>
            </div>
        </div>

        <!-- PR Approved -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">PR Approved</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($prApproved) }}</p>
                </div>
            </div>
        </div>

        <!-- PO Bulan Ini -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">PO Bulan Ini</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($poBulanIni) }}</p>
                </div>
            </div>
        </div>

        <!-- Outstanding PO -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 dark:bg-red-900">
                    <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">PO Outstanding</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($poBelumLunas->count()) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Purchase Request Status & Top Suppliers -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Purchase Request Status -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status Purchase Request</h3>
            <div class="space-y-4">
                @php
                    $statusColors = [
                        'draft' => [
                            'bg' => 'bg-gray-100 dark:bg-gray-700',
                            'text' => 'text-gray-800 dark:text-gray-200',
                            'label' => 'Draft',
                        ],
                        'diajukan' => [
                            'bg' => 'bg-yellow-100 dark:bg-yellow-900',
                            'text' => 'text-yellow-600 dark:text-yellow-400',
                            'label' => 'Diajukan',
                        ],
                        'disetujui' => [
                            'bg' => 'bg-green-100 dark:bg-green-900',
                            'text' => 'text-green-600 dark:text-green-400',
                            'label' => 'Disetujui',
                        ],
                        'ditolak' => [
                            'bg' => 'bg-red-100 dark:bg-red-900',
                            'text' => 'text-red-600 dark:text-red-400',
                            'label' => 'Ditolak',
                        ],
                        'selesai' => [
                            'bg' => 'bg-blue-100 dark:bg-blue-900',
                            'text' => 'text-blue-600 dark:text-blue-400',
                            'label' => 'Selesai',
                        ],
                    ];
                @endphp

                @foreach ($statusColors as $status => $config)
                    <div class="flex items-center justify-between p-3 rounded-lg {{ $config['bg'] }}">
                        <span class="font-medium {{ $config['text'] }}">{{ $config['label'] }}</span>
                        <span class="text-xl font-bold {{ $config['text'] }}">
                            {{ $purchaseRequestStats[$status] ?? 0 }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Top Suppliers -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Supplier Bulan Ini</h3>
            <div class="space-y-3">
                @forelse($topSuppliers as $index => $supplier)
                    <div
                        class="flex items-center justify-between p-3 rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                <span
                                    class="text-sm font-bold text-blue-600 dark:text-blue-400">{{ $index + 1 }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $supplier->nama }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Total: Rp
                                    {{ number_format($supplier->total_pembelian ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">Tidak ada data supplier bulan ini</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Purchase Orders -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Purchase Order Terbaru</h3>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @forelse($purchaseOrderTerbaru as $po)
                    <div
                        class="flex items-center justify-between p-3 rounded-lg border border-gray-200 dark:border-gray-700">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $po->nomor }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $po->supplier->nama ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $po->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">Rp
                                {{ number_format($po->total ?? 0, 0, ',', '.') }}</p>
                            <span
                                class="px-2 py-1 text-xs font-medium rounded-full 
                                @if ($po->status == 'draft') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                @elseif($po->status == 'confirmed') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                @elseif($po->status == 'received') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                {{ ucfirst($po->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">Tidak ada purchase order terbaru</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Goods Receipt -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Penerimaan Barang Terbaru</h3>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @forelse($penerimaanTerbaru as $penerimaan)
                    <div
                        class="flex items-center justify-between p-3 rounded-lg border border-gray-200 dark:border-gray-700">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $penerimaan->nomor }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">PO:
                                {{ $penerimaan->purchaseOrder->nomor ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $penerimaan->supplier->nama ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">
                                {{ $penerimaan->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="text-right">
                            <span
                                class="px-2 py-1 text-xs font-medium rounded-full 
                                @if ($penerimaan->status == 'draft') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                @elseif($penerimaan->status == 'received') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @endif">
                                {{ ucfirst($penerimaan->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">Tidak ada penerimaan barang terbaru</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Outstanding Purchase Orders -->
    @if ($poBelumLunas->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Purchase Order Outstanding</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                No. PO</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Supplier</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Tanggal</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Total</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($poBelumLunas as $po)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $po->nomor }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $po->supplier->nama ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ \Carbon\Carbon::parse($po->tanggal)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    Rp {{ number_format($po->total, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        {{ ucfirst(str_replace('_', ' ', $po->status_pembayaran)) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aksi Cepat</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @if (auth()->user()->hasPermission('purchase_request.create'))
                <a href="{{ route('pembelian.permintaan-pembelian.create') }}"
                    class="flex flex-col items-center p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400 mb-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Buat PR</span>
                </a>
            @endif

            @if (auth()->user()->hasPermission('purchase_order.create'))
                <a href="{{ route('pembelian.purchasing-order.create') }}"
                    class="flex flex-col items-center p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400 mb-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Buat PO</span>
                </a>
            @endif

            @if (auth()->user()->hasPermission('supplier.create'))
                <a href="master-data/supplier"
                    class="flex flex-col items-center p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400 mb-2" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Tambah Supplier</span>
                </a>
            @endif

            @if (auth()->user()->hasPermission('laporan_pembelian.view'))
                <a href="{{ route('laporan.pembelian.index') }}"
                    class="flex flex-col items-center p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400 mb-2" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Laporan Pembelian</span>
                </a>
            @endif
        </div>
    </div>

</x-app-layout>
