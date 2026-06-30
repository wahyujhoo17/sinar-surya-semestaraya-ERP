<x-app-layout>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Dashboard Analitik CRM</h1>
            <div class="flex items-center space-x-2">
                <a href="{{ route('crm.pipeline.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Lihat Pipeline</a>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-700"
             x-data="{ filterType: '{{ $filter_type ?? 'year' }}' }">
            <form action="{{ route('crm.laporan.index') }}" method="GET" class="flex flex-col md:flex-row items-end gap-4">
                
                <!-- Filter Type -->
                <div class="w-full md:w-1/4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe Filter</label>
                    <select name="filter_type" x-model="filterType"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                        <option value="year">Tahunan</option>
                        <option value="month">Bulanan</option>
                        <option value="custom">Rentang Tanggal</option>
                    </select>
                </div>

                <!-- Year Input (Visible for year and month) -->
                <div class="w-full md:w-1/4" x-show="filterType === 'year' || filterType === 'month'">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun</label>
                    <input type="number" name="year" value="{{ $year ?? date('Y') }}" min="2000" max="2100"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>

                <!-- Month Input (Visible only for month) -->
                <div class="w-full md:w-1/4" x-show="filterType === 'month'" x-cloak>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bulan</label>
                    <select name="month" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ ($month ?? date('m')) == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                            </option>
                        @endfor
                    </select>
                </div>

                <!-- Date Range Inputs (Visible only for custom) -->
                <div class="w-full md:w-1/4" x-show="filterType === 'custom'" x-cloak>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ $start_date ?? '' }}"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
                <div class="w-full md:w-1/4" x-show="filterType === 'custom'" x-cloak>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $end_date ?? '' }}"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>

                <div class="w-full md:w-auto">
                    <button type="submit" class="px-4 py-2 bg-gray-800 dark:bg-gray-600 text-white rounded-md hover:bg-gray-700 w-full md:w-auto shadow-sm transition-colors">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 border border-gray-200 dark:border-gray-700">
                <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">Prospek Aktif</h3>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalAktif }}</span>
                    <span class="text-sm text-gray-500">Prospek</span>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 border border-gray-200 dark:border-gray-700">
                <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">Win Rate</h3>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $winRate }}%</span>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 border border-gray-200 dark:border-gray-700">
                <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">Pipeline Value (Aktif)</h3>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-xl font-bold text-blue-600 dark:text-blue-400">Rp {{ number_format($totalPotensiAktif, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 border border-gray-200 dark:border-gray-700">
                <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Revenue Won</h3>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400">Rp {{ number_format($totalPotensiWon, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Pipeline Funnel -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Pipeline Funnel (Count)</h3>
                <div class="space-y-4">
                    @php
                        $maxFunnel = max(1, max($funnelData));
                    @endphp
                    @foreach (['baru' => 'Baru', 'tertarik' => 'Tertarik', 'negosiasi' => 'Negosiasi', 'menjadi_customer' => 'Menjadi Customer', 'menolak' => 'Menolak/Lost'] as $key => $label)
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $label }}</span>
                                <span class="text-gray-500">{{ $funnelData[$key] }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                @php
                                    $width = ($funnelData[$key] / $maxFunnel) * 100;
                                    $color = match($key) {
                                        'baru' => 'bg-yellow-400',
                                        'tertarik' => 'bg-blue-500',
                                        'negosiasi' => 'bg-indigo-500',
                                        'menjadi_customer' => 'bg-green-500',
                                        'menolak' => 'bg-red-500',
                                        default => 'bg-gray-500'
                                    };
                                @endphp
                                <div class="{{ $color }} h-2.5 rounded-full" style="width: {{ $width }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Pipeline Deal Value -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Pipeline Deal Value (Rp)</h3>
                <div class="space-y-4">
                    @php
                        $maxValue = max(1, max($valueData));
                    @endphp
                    @foreach (['baru' => 'Baru', 'tertarik' => 'Tertarik', 'negosiasi' => 'Negosiasi', 'menjadi_customer' => 'Menjadi Customer'] as $key => $label)
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $label }}</span>
                                <span class="text-gray-500">Rp {{ number_format($valueData[$key], 0, ',', '.') }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                @php
                                    $width = ($valueData[$key] / $maxValue) * 100;
                                    $color = match($key) {
                                        'baru' => 'bg-yellow-400',
                                        'tertarik' => 'bg-blue-500',
                                        'negosiasi' => 'bg-indigo-500',
                                        'menjadi_customer' => 'bg-green-500',
                                        default => 'bg-gray-500'
                                    };
                                @endphp
                                <div class="{{ $color }} h-2.5 rounded-full" style="width: {{ $width }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sales Performance Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Performa Sales</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-6 py-3">Nama Sales</th>
                            <th class="px-6 py-3 text-center">Total Leads</th>
                            <th class="px-6 py-3 text-center">Leads Aktif</th>
                            <th class="px-6 py-3 text-center">Won</th>
                            <th class="px-6 py-3 text-center">Win Rate</th>
                            <th class="px-6 py-3 text-right">Value Aktif</th>
                            <th class="px-6 py-3 text-right">Value Won</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salesPerformance as $sales)
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $sales->name }}
                            </td>
                            <td class="px-6 py-4 text-center">{{ $sales->total_prospek }}</td>
                            <td class="px-6 py-4 text-center">{{ $sales->active_prospek }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full dark:bg-green-900/30 dark:text-green-400">{{ $sales->won_prospek }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-semibold {{ $sales->win_rate >= 50 ? 'text-green-600' : 'text-gray-900 dark:text-gray-300' }}">{{ $sales->win_rate }}%</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                Rp {{ number_format($sales->total_value_active, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-indigo-600 dark:text-indigo-400">
                                Rp {{ number_format($sales->total_value_won, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                Belum ada data performa sales.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
