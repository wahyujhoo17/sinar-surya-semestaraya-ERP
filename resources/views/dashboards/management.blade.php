<x-app-layout :breadcrumbs="[]" :currentPage="__('Dashboard Manajemen')">

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

            .growth-positive {
                color: #10b981;
            }

            .growth-negative {
                color: #ef4444;
            }
        </style>
    @endpush

    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard Manajemen</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Ringkasan eksekutif dan KPI perusahaan</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ now()->format('l, d F Y') }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500">{{ now()->format('H:i') }} WIB</p>
            </div>
        </div>
    </div>

    <!-- Key Performance Indicators -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Produk -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Produk</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalProduk) }}</p>
                </div>
            </div>
        </div>

        <!-- Total Pelanggan -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Pelanggan</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalPelanggan) }}</p>
                </div>
            </div>
        </div>

        <!-- Total Supplier -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Supplier</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalSupplier) }}</p>
                </div>
            </div>
        </div>

        <!-- Total Karyawan -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                    <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m3 5.197H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Karyawan</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalKaryawan) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Sales Performance -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Performa Penjualan</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Penjualan Bulan Ini</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp
                        {{ number_format($penjualanBulanIni, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Pertumbuhan</p>
                    <p
                        class="text-lg font-semibold {{ $pertumbuhanPenjualan >= 0 ? 'growth-positive' : 'growth-negative' }}">
                        {{ $pertumbuhanPenjualan >= 0 ? '+' : '' }}{{ number_format($pertumbuhanPenjualan, 1) }}%
                    </p>
                </div>
            </div>
        </div>

        <!-- Receivables -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Piutang</h3>
            <div class="text-center">
                <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">Rp
                    {{ number_format($totalPiutang, 0, ',', '.') }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Total Piutang Outstanding</p>
            </div>
        </div>

        <!-- Payables -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Hutang</h3>
            <div class="text-center">
                <p class="text-3xl font-bold text-red-600 dark:text-red-400">Rp
                    {{ number_format($totalHutang, 0, ',', '.') }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Total Hutang Outstanding</p>
            </div>
        </div>
    </div>

    <!-- Operational Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Work Orders -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Work Orders Aktif</h3>
                <span
                    class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-medium px-2.5 py-0.5 rounded">
                    {{ $workOrderAktif }}
                </span>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400">Work orders yang sedang berjalan</p>
        </div>

        <!-- Pending Deliveries -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pengiriman Pending</h3>
                <span
                    class="bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 text-xs font-medium px-2.5 py-0.5 rounded">
                    {{ $deliveryPending }}
                </span>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400">Delivery orders yang menunggu</p>
        </div>

        <!-- Purchase Requests -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">PR Pending</h3>
                <span
                    class="bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 text-xs font-medium px-2.5 py-0.5 rounded">
                    {{ $prPending }}
                </span>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400">Purchase requests menunggu approval</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Sales Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Trend Penjualan (6 Bulan Terakhir)</h3>
            <div class="h-64">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Product Category Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Distribusi Produk per Kategori</h3>
            <div class="h-64">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aktivitas Terbaru</h3>
        <div class="space-y-3">
            @forelse($aktivitasTerbaru as $aktivitas)
                <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                    <div class="flex-shrink-0">
                        <div
                            class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $aktivitas->user ? $aktivitas->user->name : 'System' }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $aktivitas->deskripsi }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-500">
                            {{ $aktivitas->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400 text-center py-4">Tidak ada aktivitas terbaru</p>
            @endforelse
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Sales Chart
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(collect($penjualanPerBulan)->pluck('bulan')) !!},
                    datasets: [{
                        label: 'Penjualan',
                        data: {!! json_encode(collect($penjualanPerBulan)->pluck('total')) !!},
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });

            // Category Chart
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode(collect($produkPerKategori)->pluck('nama')) !!},
                    datasets: [{
                        data: {!! json_encode(collect($produkPerKategori)->pluck('total')) !!},
                        backgroundColor: [
                            'rgb(59, 130, 246)',
                            'rgb(16, 185, 129)',
                            'rgb(245, 158, 11)',
                            'rgb(239, 68, 68)',
                            'rgb(139, 92, 246)',
                            'rgb(236, 72, 153)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        </script>
    @endpush

</x-app-layout>
