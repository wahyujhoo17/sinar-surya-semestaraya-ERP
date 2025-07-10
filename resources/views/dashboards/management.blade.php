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

            /* Chart container for better responsiveness */
            .chart-responsive {
                position: relative;
                width: 100%;
                aspect-ratio: 2/1;
                min-height: 180px;
                max-height: 400px;
                margin-bottom: 1.5rem;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            @media (max-width: 1024px) {
                .chart-responsive {
                    aspect-ratio: 1.5/1;
                    min-height: 140px;
                }
            }

            @media (max-width: 640px) {
                .chart-responsive {
                    aspect-ratio: 1/1;
                    min-height: 100px;
                }
            }

            .chart-responsive canvas {
                width: 100% !important;
                height: 100% !important;
                max-width: 100% !important;
                max-height: 100% !important;
                display: block;
            }

            /* Chart.js custom tooltip styling */
            .chartjs-tooltip {
                opacity: 1;
                position: absolute;
                background: rgba(30, 41, 59, 0.95);
                color: #fff;
                border-radius: 8px;
                padding: 8px 14px;
                pointer-events: none;
                font-size: 0.95rem;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
                z-index: 10;
                transition: all 0.15s;
            }

            .chartjs-tooltip strong {
                color: #38bdf8;
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
            <div class="chart-responsive">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Product Category Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Distribusi Produk per Kategori</h3>
            <div class="chart-responsive">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center">
                <svg class="w-4 h-4 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Aktivitas Terbaru
            </h3>
            <span
                class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full">Real-time</span>
        </div>
        <ul class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($aktivitasTerbaru->take(5) as $aktivitas)
                <li class="flex items-center py-2">
                    <div class="flex-shrink-0 mr-3">
                        @php
                            $iconColors = [
                                'create' => 'bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400',
                                'update' => 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400',
                                'delete' => 'bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400',
                                'login' => 'bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400',
                                'export' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400',
                                'default' => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400',
                            ];
                            $colorClass = $iconColors[$aktivitas->aktivitas] ?? $iconColors['default'];
                        @endphp
                        <div class="w-8 h-8 {{ $colorClass }} rounded-full flex items-center justify-center">
                            @if ($aktivitas->aktivitas === 'create')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            @elseif($aktivitas->aktivitas === 'update')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            @elseif($aktivitas->aktivitas === 'delete')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            @elseif($aktivitas->aktivitas === 'login')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                                    </path>
                                </svg>
                            @elseif($aktivitas->aktivitas === 'export')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <span
                                class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $aktivitas->user ? $aktivitas->user->name : 'System' }}</span>
                            <span
                                class="ml-2 text-xs px-2 py-0.5 rounded bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">{{ $aktivitas->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 mt-1">
                            @if ($aktivitas->modul)
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">
                                    {{ ucfirst(str_replace('_', ' ', $aktivitas->modul)) }}
                                </span>
                            @endif
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                @if ($aktivitas->aktivitas === 'create') bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300
                                @elseif($aktivitas->aktivitas === 'update') bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300
                                @elseif($aktivitas->aktivitas === 'delete') bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300
                                @elseif($aktivitas->aktivitas === 'login') bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300
                                @elseif($aktivitas->aktivitas === 'export') bg-yellow-50 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300
                                @else bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 @endif">
                                {{ ucfirst($aktivitas->aktivitas) }}
                            </span>
                            @if ($aktivitas->ip_address)
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9m0 9a9 9 0 01-9-9m9 9c0-5 4-9 9-9s9 4 9 9">
                                        </path>
                                    </svg>
                                    {{ $aktivitas->ip_address }}
                                </span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                            @php
                                $detail = $aktivitas->detail;
                                $isJson = false;
                                if (
                                    is_string($detail) &&
                                    (str_starts_with($detail, '{') || str_starts_with($detail, '['))
                                ) {
                                    $json = json_decode($detail, true);
                                    $isJson = json_last_error() === JSON_ERROR_NONE && is_array($json);
                                } else {
                                    $json = null;
                                }
                            @endphp
                            @if ($isJson && $json)
                                <div class="flex flex-wrap gap-2">
                                    @foreach (['nomor', 'customer', 'total', 'user'] as $key)
                                        @if (isset($json[$key]))
                                            <span
                                                class="inline-block bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-2 py-0.5 rounded">
                                                <strong>{{ ucfirst($key) }}:</strong> {{ $json[$key] }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            @elseif($detail)
                                {{ is_string($detail) ? Str::limit(strip_tags($detail), 80) : '' }}
                            @else
                                {{ ucfirst($aktivitas->aktivitas) }}
                                {{ $aktivitas->modul ? 'pada modul ' . str_replace('_', ' ', $aktivitas->modul) : '' }}
                            @endif
                        </div>
                </li>
            @empty
                <li class="text-center py-8 text-gray-500 dark:text-gray-400 text-sm">
                    <svg class="w-8 h-8 mx-auto mb-2 text-gray-400 dark:text-gray-500" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Tidak ada aktivitas
                </li>
            @endforelse
        </ul>
        @if ($aktivitasTerbaru->count() > 5)
            <div class="mt-3 text-center">
                <a href="pengaturan/log-aktivitas"
                    class="inline-flex items-center text-xs font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 transition-colors">
                    Lihat semua aktivitas
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </a>
            </div>
        @endif
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Sales Chart
            (function() {
                const salesCtx = document.getElementById('salesChart').getContext('2d');
                new Chart(salesCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode(collect($penjualanPerBulan)->pluck('bulan')) !!},
                        datasets: [{
                            label: 'Penjualan',
                            data: {!! json_encode(collect($penjualanPerBulan)->pluck('total')) !!},
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: getGradient(salesCtx),
                            pointBackgroundColor: 'rgb(59, 130, 246)',
                            pointRadius: 5,
                            pointHoverRadius: 8,
                            tension: 0.4,
                            fill: true,
                            borderWidth: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(30,41,59,0.95)',
                                titleColor: '#38bdf8',
                                bodyColor: '#fff',
                                borderColor: '#38bdf8',
                                borderWidth: 1,
                                padding: 12,
                                cornerRadius: 8,
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(59,130,246,0.08)'
                                },
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });

                // Gradient background for line chart
                function getGradient(ctx) {
                    const gradient = ctx.createLinearGradient(0, 0, 0, 350);
                    gradient.addColorStop(0, 'rgba(59,130,246,0.25)');
                    gradient.addColorStop(1, 'rgba(59,130,246,0.02)');
                    return gradient;
                }
            })();

            // Category Chart
            (function() {
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
                            ],
                            borderWidth: 2,
                            borderColor: '#fff',
                            hoverOffset: 12
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: '#334155',
                                    font: {
                                        size: 14,
                                        weight: 'bold'
                                    },
                                    padding: 18,
                                    boxWidth: 18
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(30,41,59,0.95)',
                                titleColor: '#38bdf8',
                                bodyColor: '#fff',
                                borderColor: '#38bdf8',
                                borderWidth: 1,
                                padding: 12,
                                cornerRadius: 8,
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        return context.label + ': ' + context.parsed + ' produk';
                                    }
                                }
                            }
                        }
                    }
                });
            })();
        </script>
    @endpush

</x-app-layout>
