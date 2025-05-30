<x-app-layout>
    <x-slot name="header">
        <div
            class="bg-gradient-to-r from-indigo-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-lg shadow-sm px-4 py-3 border border-indigo-100 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight flex items-center">
                    <div class="bg-indigo-600 dark:bg-indigo-500 p-2 rounded-lg shadow-sm mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <span
                            class="block text-xs text-indigo-600 dark:text-indigo-400 font-medium mb-0.5">Dashboard</span>
                        Laporan QC & Statistik Retur
                    </div>
                </h2>
                <div
                    class="flex items-center space-x-2 bg-white dark:bg-gray-800 px-3 py-2 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span id="current-date" class="text-sm font-medium text-gray-700 dark:text-gray-300"></span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border border-gray-100 dark:border-gray-700">


                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Filter Period -->
                    <div
                        class="mb-8 bg-gradient-to-r from-blue-50 via-indigo-50 to-white dark:from-gray-800 dark:via-gray-700 dark:to-gray-900 p-5 rounded-lg border border-blue-100 dark:border-gray-600 shadow-md">
                        <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 flex items-center">
                            <div class="bg-indigo-500 dark:bg-indigo-600 rounded-full p-1.5 mr-2 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                            </div>
                            Filter Data
                        </h5>
                        <form method="GET" action="{{ route('penjualan.retur.qc-report') }}">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="space-y-2">
                                    <label for="start_date"
                                        class="block text-sm font-medium text-gray-600 dark:text-gray-400">Tanggal
                                        Mulai</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-indigo-400" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="date" name="start_date" id="start_date"
                                            class="pl-10 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            value="{{ request('start_date', now()->subDays(30)->format('Y-m-d')) }}">
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label for="end_date"
                                        class="block text-sm font-medium text-gray-600 dark:text-gray-400">Tanggal
                                        Akhir</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-indigo-400" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="date" name="end_date" id="end_date"
                                            class="pl-10 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            value="{{ request('end_date', now()->format('Y-m-d')) }}">
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label for="status"
                                        class="block text-sm font-medium text-gray-600 dark:text-gray-400">Status</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-indigo-400" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <select name="status" id="status"
                                            class="pl-10 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <option value="">Semua Status</option>
                                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>
                                                Draft</option>
                                            <option value="menunggu_persetujuan"
                                                {{ request('status') == 'menunggu_persetujuan' ? 'selected' : '' }}>
                                                Menunggu Persetujuan</option>
                                            <option value="disetujui"
                                                {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui
                                            </option>
                                            <option value="ditolak"
                                                {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                            <option value="diproses"
                                                {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses
                                            </option>
                                            <option value="selesai"
                                                {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="flex items-end space-x-2">
                                    <button type="submit"
                                        class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 flex items-center transition-all duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                        </svg>
                                        Filter
                                    </button>
                                    <a href="{{ route('penjualan.retur.qc-report') }}"
                                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 flex items-center transition-all duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Quality Control Statistics -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <!-- QC Stats Card -->
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700">
                            <div
                                class="border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-indigo-50 to-white dark:from-gray-800 dark:to-gray-900 px-4 py-3">
                                <h5 class="font-semibold text-gray-700 dark:text-gray-300 flex items-center">
                                    <div class="bg-indigo-500 dark:bg-indigo-600 rounded-full p-1.5 mr-2 shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                    </div>
                                    Statistik Quality Control
                                </h5>
                            </div>
                            <div class="p-4">
                                <div class="grid grid-cols-2 gap-4 mb-6">
                                    <!-- Passed QC Card -->
                                    <div
                                        class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 rounded-lg shadow-sm overflow-hidden border border-green-200 dark:border-green-800 transform transition-transform duration-200 hover:scale-105">
                                        <div class="p-4 flex items-center">
                                            <div class="rounded-full bg-green-500 p-2 mr-4 shadow-md">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-green-800 dark:text-green-300">Lolos
                                                    QC</p>
                                                <h3 class="text-2xl font-bold text-green-700 dark:text-green-200">
                                                    {{ $qcPassedCount ?? 0 }}</h3>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Failed QC Card -->
                                    <div
                                        class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900 dark:to-red-800 rounded-lg shadow-sm overflow-hidden border border-red-200 dark:border-red-800 transform transition-transform duration-200 hover:scale-105">
                                        <div class="p-4 flex items-center">
                                            <div class="rounded-full bg-red-500 p-2 mr-4 shadow-md">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-red-800 dark:text-red-300">Gagal QC
                                                </p>
                                                <h3 class="text-2xl font-bold text-red-700 dark:text-red-200">
                                                    {{ $qcFailedCount ?? 0 }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- QC Progress Bar -->
                                @php
                                    $totalQc = ($qcPassedCount ?? 0) + ($qcFailedCount ?? 0);
                                    $qcPassPercentage = $totalQc > 0 ? round(($qcPassedCount / $totalQc) * 100) : 0;
                                @endphp
                                <div class="mb-1 text-sm font-medium flex justify-between">
                                    <span class="text-gray-700 dark:text-gray-300">Progress QC</span>
                                    <span class="text-gray-900 dark:text-white font-semibold">{{ $qcPassPercentage }}%
                                        Lolos</span>
                                </div>
                                <div
                                    class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mb-4 overflow-hidden shadow-inner">
                                    <div class="bg-gradient-to-r from-green-400 to-green-600 h-2.5 rounded-full transition-all duration-500 ease-out"
                                        style="width: {{ $qcPassPercentage }}%"></div>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 text-center mt-2 font-medium">
                                    Total QC: {{ $totalQc }} retur dalam periode ini
                                </div>
                            </div>
                        </div>

                        <!-- Return Reason Analysis -->
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700">
                            <div
                                class="border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-indigo-50 to-white dark:from-gray-800 dark:to-gray-900 px-4 py-3">
                                <h5 class="font-semibold text-gray-700 dark:text-gray-300 flex items-center">
                                    <div class="bg-indigo-500 dark:bg-indigo-600 rounded-full p-1.5 mr-2 shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                                        </svg>
                                    </div>
                                    Analisis Alasan Retur
                                </h5>
                            </div>
                            <div class="p-4">
                                <div class="chart-container" style="position: relative; height:230px;">
                                    <canvas id="returnReasonChart"></canvas>
                                    <div id="noDataChart"
                                        class="absolute inset-0 flex items-center justify-center flex-col bg-gray-50 dark:bg-gray-800 bg-opacity-80 dark:bg-opacity-80 rounded-lg hidden">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-2"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                                        </svg>
                                        <p class="text-gray-600 dark:text-gray-400 font-medium">Tidak ada data alasan
                                            retur</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Coba pilih rentang
                                            tanggal yang berbeda</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-4">
                                    <div class="flex items-center space-x-2 text-sm">
                                        <span class="inline-block w-3 h-3 rounded-full bg-indigo-500"></span>
                                        <span class="text-gray-700 dark:text-gray-300">Cacat Produk</span>
                                    </div>
                                    <div class="flex items-center space-x-2 text-sm">
                                        <span class="inline-block w-3 h-3 rounded-full bg-green-500"></span>
                                        <span class="text-gray-700 dark:text-gray-300">Salah Pengiriman</span>
                                    </div>
                                    <div class="flex items-center space-x-2 text-sm">
                                        <span class="inline-block w-3 h-3 rounded-full bg-yellow-500"></span>
                                        <span class="text-gray-700 dark:text-gray-300">Kualitas Tidak Sesuai</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent QC Activities -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700">
                        <div
                            class="border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-indigo-50 to-white dark:from-gray-800 dark:to-gray-900 px-4 py-3">
                            <h5 class="font-semibold text-gray-700 dark:text-gray-300 flex items-center">
                                <div class="bg-indigo-500 dark:bg-indigo-600 rounded-full p-1.5 mr-2 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                Aktivitas Quality Control Terbaru
                            </h5>
                        </div>
                        <div class="p-4">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead
                                        class="bg-gradient-to-r from-gray-50 to-white dark:from-gray-700 dark:to-gray-800">
                                        <tr>
                                            <th scope="col"
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Nomor Retur</th>
                                            <th scope="col"
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Customer</th>
                                            <th scope="col"
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Tanggal QC</th>
                                            <th scope="col"
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Petugas QC</th>
                                            <th scope="col"
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Status QC</th>
                                            <th scope="col"
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Catatan</th>
                                            <th scope="col"
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @forelse($qcActivities ?? [] as $activity)
                                            <tr
                                                class="hover:bg-indigo-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                                <td
                                                    class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $activity->nomor }}
                                                </td>
                                                <td
                                                    class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                    {{ $activity->customer->nama ?? 'N/A' }}
                                                </td>
                                                <td
                                                    class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                    {{ $activity->qc_at ? date('d/m/Y H:i', strtotime($activity->qc_at)) : 'Belum QC' }}
                                                </td>
                                                <td
                                                    class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                    {{ $activity->qcByUser->name ?? 'N/A' }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                    @if ($activity->qc_passed === true)
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 shadow-sm">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-3.5 w-3.5 mr-1 text-green-500" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            Lolos
                                                        </span>
                                                    @elseif($activity->qc_passed === false)
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 shadow-sm">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-3.5 w-3.5 mr-1 text-red-500" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                            Gagal
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 shadow-sm">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-3.5 w-3.5 mr-1 text-yellow-500"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            Belum Diproses
                                                        </span>
                                                    @endif
                                                </td>
                                                <td
                                                    class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 max-w-xs truncate">
                                                    {{ $activity->qc_notes ?? 'Tidak ada catatan' }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                                    <a href="{{ route('penjualan.retur.show', $activity->id) }}"
                                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7"
                                                    class="px-4 py-8 text-sm text-gray-500 dark:text-gray-400 text-center">
                                                    <div
                                                        class="flex flex-col items-center justify-center p-6 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-100 dark:border-gray-600">
                                                        <div
                                                            class="bg-gray-200 dark:bg-gray-600 p-3 rounded-full mb-3">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-8 w-8 text-gray-400 dark:text-gray-300"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                            </svg>
                                                        </div>
                                                        <p class="font-medium text-gray-600 dark:text-gray-300">Tidak
                                                            ada data aktivitas QC</p>
                                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Coba
                                                            ubah filter atau periode waktu untuk melihat data</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Render Return Reason Chart dengan animasi yang ditingkatkan
                const reasonCtx = document.getElementById('returnReasonChart').getContext('2d');
                const noDataDiv = document.getElementById('noDataChart');

                // Tambahkan variabel untuk chart
                let returnReasonChart = null;

                // Data dari controller
                const reasonLabels = {!! json_encode($reasonLabels ?? []) !!};
                const reasonData = {!! json_encode($reasonData ?? []) !!};

                // Cek apakah data tersedia
                const hasData = reasonLabels && reasonData && reasonLabels.length > 0 && reasonData.length > 0;

                // Hapus chart lama jika sudah ada sebelumnya
                if (window.returnReasonChart) {
                    window.returnReasonChart.destroy();
                }

                if (!hasData) {
                    // Tampilkan pesan "tidak ada data" jika tidak ada data
                    noDataDiv.classList.remove('hidden');
                } else {
                    // Jika ada data, buat chart
                    noDataDiv.classList.add('hidden');

                    // Color array untuk chart
                    const colorPalette = [
                        '#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#06b6d4',
                        '#8b5cf6', '#ec4899', '#14b8a6', '#f97316', '#6366f1'
                    ];

                    // Membuat chart dengan animasi yang ditingkatkan
                    window.returnReasonChart = new Chart(reasonCtx, {
                        type: 'pie',
                        data: {
                            labels: reasonLabels,
                            datasets: [{
                                data: reasonData,
                                backgroundColor: colorPalette,
                                borderWidth: 2,
                                borderColor: '#ffffff',
                                hoverOffset: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false, // Sembunyikan legend bawaan karena kita membuat custom legend
                                    position: 'right',
                                    labels: {
                                        boxWidth: 15,
                                        padding: 15,
                                        font: {
                                            size: 12
                                        }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    titleFont: {
                                        size: 14,
                                        weight: 'bold'
                                    },
                                    bodyFont: {
                                        size: 13
                                    },
                                    padding: 12,
                                    borderColor: 'rgba(255, 255, 255, 0.1)',
                                    borderWidth: 1,
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.label || '';
                                            let value = context.raw || 0;
                                            let sum = context.dataset.data.reduce((a, b) => a + b, 0);
                                            let percentage = Math.round((value / sum) * 100) + '%';
                                            return `${label}: ${value} (${percentage})`;
                                        }
                                    }
                                }
                            },
                            animation: {
                                animateScale: true,
                                animateRotate: true,
                                duration: 1500,
                                easing: 'easeOutQuart'
                            }
                        }
                    });
                }

                // Update the legend based on actual data
                updateChartLegend(reasonLabels, reasonData);

                // Mejorar visualización de fecha current
                const currentDate = document.getElementById('current-date');
                if (currentDate) {
                    const now = new Date();

                    // Opciones de formato para fecha en bahasa Indonesia
                    const options = {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    };

                    // Formato más atractivo para la fecha
                    currentDate.textContent = now.toLocaleDateString('id-ID', options);

                    // Agregar una clase para la animación de fade-in
                    currentDate.classList.add('animate-pulse');
                    setTimeout(() => {
                        currentDate.classList.remove('animate-pulse');
                    }, 1500);
                }
            });

            // Function to update the chart legend based on actual data
            function updateChartLegend(labels, data) {
                const legendContainer = document.querySelector('.chart-container + .grid');
                if (!legendContainer) return;

                // Clear existing legends
                legendContainer.innerHTML = '';

                // If no labels or data, return
                if (!labels || labels.length === 0 || !data || data.length === 0) return;

                // Calculate total for percentages
                const total = data.reduce((sum, value) => sum + value, 0);

                // Color array matching the chart colors
                const colors = [
                    '#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#06b6d4', '#8b5cf6',
                    '#ec4899', '#14b8a6', '#f97316', '#6366f1'
                ];

                // Create legend items with counts and percentages
                labels.forEach((label, index) => {
                    const color = colors[index % colors.length];
                    const value = data[index];
                    const percentage = Math.round((value / total) * 100);

                    const legendItem = document.createElement('div');
                    legendItem.className = 'flex items-center space-x-2 text-sm';
                    legendItem.innerHTML = `
                        <span class="inline-block w-3 h-3 rounded-full" style="background-color: ${color}"></span>
                        <span class="text-gray-700 dark:text-gray-300">${label}</span>
                        <span class="text-gray-500 dark:text-gray-400 text-xs">(${value} - ${percentage}%)</span>
                    `;
                    legendContainer.appendChild(legendItem);
                });
            }
        </script>
    </x-slot>
</x-app-layout>
