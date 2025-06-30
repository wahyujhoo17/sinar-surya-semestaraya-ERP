<x-app-layout :breadcrumbs="[]" :currentPage="__('Dashboard Keuangan')">

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

            .aging-bar {
                height: 6px;
                border-radius: 3px;
                transition: all 0.3s;
            }
        </style>
    @endpush

    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard Keuangan</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Ringkasan keuangan dan cash flow perusahaan</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ now()->format('l, d F Y') }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500">{{ now()->format('H:i') }} WIB</p>
            </div>
        </div>
    </div>

    <!-- Financial Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Kas -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Kas & Bank</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp
                        {{ number_format($totalKas, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Total Piutang -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Piutang</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp
                        {{ number_format(array_sum($piutangAging), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Faktur Jatuh Tempo -->
        <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 dark:bg-red-900">
                    <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Jatuh Tempo (7 Hari)</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $fakturJatuhTempo->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Piutang Aging Analysis -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Analisis Umur Piutang</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @php
                $totalPiutang = array_sum($piutangAging);
                $agingColors = ['green', 'yellow', 'orange', 'red'];
                $agingLabels = ['0-30 Hari', '31-60 Hari', '61-90 Hari', '>90 Hari'];
            @endphp

            @foreach (['0-30', '31-60', '61-90', '>90'] as $index => $period)
                <div class="p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-2">
                        <span
                            class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $agingLabels[$index] }}</span>
                        <span class="text-xs text-gray-500">
                            {{ $totalPiutang > 0 ? number_format(($piutangAging[$period] / $totalPiutang) * 100, 1) : 0 }}%
                        </span>
                    </div>
                    <p class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                        Rp {{ number_format($piutangAging[$period], 0, ',', '.') }}
                    </p>
                    <div class="aging-bar bg-{{ $agingColors[$index] }}-500"
                        style="width: {{ $totalPiutang > 0 ? ($piutangAging[$period] / $totalPiutang) * 100 : 0 }}%">
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Charts and Recent Transactions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Monthly Financial Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Pendapatan vs Pengeluaran (6 Bulan)
            </h3>
            <div class="h-64">
                <canvas id="financialChart"></canvas>
            </div>
        </div>

        <!-- Recent Cash Transactions -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Transaksi Kas Terbaru</h3>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @forelse($transaksiTerakhir as $transaksi)
                    <div
                        class="flex items-center justify-between p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-8 h-8 rounded-full {{ $transaksi->jenis == 'masuk' ? 'bg-green-100 dark:bg-green-900' : 'bg-red-100 dark:bg-red-900' }} flex items-center justify-center">
                                @if ($transaksi->jenis == 'masuk')
                                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 12H4"></path>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $transaksi->keterangan }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $transaksi->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p
                                class="text-sm font-semibold {{ $transaksi->jenis == 'masuk' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $transaksi->jenis == 'masuk' ? '+' : '-' }}Rp
                                {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">Tidak ada transaksi kas terbaru</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Outstanding Invoices -->
    @if ($fakturJatuhTempo->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Faktur Akan Jatuh Tempo (7 Hari Ke
                Depan)</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                No. Faktur</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Pelanggan</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Jatuh Tempo</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Total</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($fakturJatuhTempo as $faktur)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $faktur->nomor }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $faktur->salesOrder->customer->nama ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ \Carbon\Carbon::parse($faktur->jatuh_tempo)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    Rp {{ number_format($faktur->total, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $daysUntilDue = \Carbon\Carbon::parse($faktur->jatuh_tempo)->diffInDays(
                                            now(),
                                            false,
                                        );
                                    @endphp
                                    @if ($daysUntilDue < 0)
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                            {{ abs($daysUntilDue) }} hari lagi
                                        </span>
                                    @elseif($daysUntilDue == 0)
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            Hari ini
                                        </span>
                                    @else
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            Terlambat {{ $daysUntilDue }} hari
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Financial Chart
            const financialCtx = document.getElementById('financialChart').getContext('2d');
            new Chart(financialCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode(collect($monthlyFinancial)->pluck('bulan')) !!},
                    datasets: [{
                        label: 'Pendapatan',
                        data: {!! json_encode(collect($monthlyFinancial)->pluck('pendapatan')) !!},
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 1
                    }, {
                        label: 'Pengeluaran',
                        data: {!! json_encode(collect($monthlyFinancial)->pluck('pengeluaran')) !!},
                        backgroundColor: 'rgba(239, 68, 68, 0.8)',
                        borderColor: 'rgb(239, 68, 68)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top'
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
        </script>
    @endpush

</x-app-layout>
