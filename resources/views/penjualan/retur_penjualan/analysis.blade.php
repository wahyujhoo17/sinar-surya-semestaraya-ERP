<x-app-layout>
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-10 w-1 rounded-full mr-3">
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Analisis Retur
                                Penjualan</h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Analisis pola retur penjualan berdasarkan alasan, produk, dan pelanggan
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('penjualan.retur.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-600 hover:bg-gray-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        {{-- Filter Section --}}
        <div
            class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Filter Analisis</h3>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('penjualan.retur.analyze') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="start_date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal
                                Mulai</label>
                            <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                                class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="end_date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal
                                Akhir</label>
                            <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                                class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="customer_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Customer</label>
                            <select name="customer_id" id="customer_id"
                                class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="">Semua Customer</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ $customerId == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->nama }}
                                        {{ $customer->company ? '(' . $customer->company . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-800 focus:outline-none focus:border-primary-800 focus:ring ring-primary-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            {{-- Reason Analysis --}}
            <div
                class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Analisis Berdasarkan Alasan</h3>
                </div>
                <div class="p-6">
                    @if (count($reasonAnalysis) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Alasan
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Jumlah
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Nilai (Rp)
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Persentase
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($reasonAnalysis as $analysis)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $analysis['reason'] }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $analysis['count'] }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ number_format($analysis['value'], 0, ',', '.') }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                <div class="flex items-center">
                                                    <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                                                        <div class="bg-primary-600 h-2.5 rounded-full"
                                                            style="width: {{ $analysis['percentage'] }}%"></div>
                                                    </div>
                                                    <span>{{ $analysis['percentage'] }}%</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-4 text-center text-gray-500 dark:text-gray-400">
                            Tidak ada data untuk ditampilkan
                        </div>
                    @endif
                </div>
            </div>

            {{-- Product Analysis --}}
            <div
                class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Analisis Berdasarkan Produk</h3>
                </div>
                <div class="p-6">
                    @if (count($productAnalysis) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Produk
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Jumlah Retur
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Nilai (Rp)
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($productAnalysis as $analysis)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $analysis['name'] }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $analysis['count'] }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ number_format($analysis['value'], 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-4 text-center text-gray-500 dark:text-gray-400">
                            Tidak ada data untuk ditampilkan
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Customer Analysis --}}
        <div
            class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Analisis Berdasarkan Customer</h3>
            </div>
            <div class="p-6">
                @if (count($customerAnalysis) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Customer
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Jumlah Retur
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Nilai Total (Rp)
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($customerAnalysis as $analysis)
                                    <tr>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $analysis['name'] }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $analysis['count'] }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ number_format($analysis['value'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="py-4 text-center text-gray-500 dark:text-gray-400">
                        Tidak ada data untuk ditampilkan
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
