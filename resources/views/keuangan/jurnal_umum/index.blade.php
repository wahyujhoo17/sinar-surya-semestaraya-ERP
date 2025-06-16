<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Overview Header --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2 text-primary-600 dark:text-primary-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Jurnal Umum
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500 dark:text-gray-500"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Kelola pencatatan jurnal akuntansi untuk PT Sinar Surya Semestaraya
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-2">
                <a href="{{ route('keuangan.jurnal-umum.create') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Jurnal Baru
                </a>
            </div>
        </div>

        {{-- Filter and Search Section --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 mb-6">
            <div class="mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-600 dark:text-primary-400 mr-2"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <h3 class="text-md font-semibold text-gray-700 dark:text-gray-300">Filter Data Jurnal</h3>
            </div>

            <form action="{{ route('keuangan.jurnal-umum.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="relative">
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tanggal Awal
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}"
                                class="pl-10 mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                        </div>
                    </div>
                    <div class="relative">
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tanggal Akhir
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}"
                                class="pl-10 mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                        </div>
                    </div>
                    <div>
                        <label for="akun_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Akun
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <select id="akun_id" name="akun_id"
                                class="pl-10 mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                <option value="">Semua Akun</option>
                                @foreach ($akuns as $akun)
                                    <option value="{{ $akun->id }}"
                                        {{ request('akun_id') == $akun->id ? 'selected' : '' }}>
                                        {{ $akun->kode }} - {{ $akun->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-3">
                        <label for="no_referensi"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Nomor Referensi
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" id="no_referensi" name="no_referensi"
                                value="{{ request('no_referensi') }}"
                                placeholder="Cari berdasarkan nomor referensi..."
                                class="pl-10 mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                        </div>
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full inline-flex justify-center py-2.5 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Journal Entries List --}}
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden">
            <div
                class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-750 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-primary-600 dark:text-primary-400 mr-2" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Daftar Jurnal Umum</h3>
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    <span
                        class="bg-primary-50 dark:bg-primary-900/30 px-2 py-1 rounded-md text-primary-700 dark:text-primary-400">
                        {{ $jurnals->total() }} entri jurnal
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead
                        class="bg-gradient-to-r from-gray-100 to-gray-50 dark:from-gray-700 dark:to-gray-800 border-b-2 border-gray-200 dark:border-gray-600">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Tanggal
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    No. Referensi
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    Jumlah Entri
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                    </svg>
                                    Keterangan
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-right text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center justify-end">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-1.5 text-emerald-500 dark:text-emerald-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Total Debit
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-right text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center justify-end">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-1.5 text-blue-500 dark:text-blue-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 12H4" />
                                    </svg>
                                    Total Kredit
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-center text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Status
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-center text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 12h16m-7 6h7" />
                                    </svg>
                                    Aksi
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @php
                            $totalDebit = 0;
                            $totalKredit = 0;
                            $groupedJurnals = [];

                            // Mengelompokkan jurnal berdasarkan no referensi dan tanggal
                            foreach ($jurnals as $jurnal) {
                                $key = $jurnal->tanggal . '-' . $jurnal->no_referensi;

                                if (!isset($groupedJurnals[$key])) {
                                    $groupedJurnals[$key] = [
                                        'id' => $jurnal->id,
                                        'tanggal' => $jurnal->tanggal,
                                        'no_referensi' => $jurnal->no_referensi,
                                        'keterangan' => $jurnal->keterangan,
                                        'total_debit' => 0,
                                        'total_kredit' => 0,
                                        'count' => 0,
                                    ];
                                }

                                $groupedJurnals[$key]['total_debit'] += $jurnal->debit;
                                $groupedJurnals[$key]['total_kredit'] += $jurnal->kredit;
                                $groupedJurnals[$key]['count']++;

                                $totalDebit += $jurnal->debit;
                                $totalKredit += $jurnal->kredit;
                            }
                        @endphp

                        @forelse ($groupedJurnals as $jurnal)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-6 py-3.5 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    {{ \Carbon\Carbon::parse($jurnal['tanggal'])->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    <span class="font-mono">{{ $jurnal['no_referensi'] }}</span>
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    <span class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-xs font-medium">
                                        {{ $jurnal['count'] }} entri
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    {{ Str::limit($jurnal['keterangan'], 40) }}
                                </td>
                                <td
                                    class="px-6 py-3.5 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300 text-right">
                                    <span class="text-emerald-600 dark:text-emerald-400 font-medium">
                                        {{ number_format($jurnal['total_debit'], 2, ',', '.') }}
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-3.5 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300 text-right">
                                    <span class="text-blue-600 dark:text-blue-400 font-medium">
                                        {{ number_format($jurnal['total_kredit'], 2, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap text-sm text-center">
                                    @if (abs($jurnal['total_debit'] - $jurnal['total_kredit']) < 0.01)
                                        <span
                                            class="bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 px-2 py-1 rounded text-xs">
                                            Seimbang
                                        </span>
                                    @else
                                        <span
                                            class="bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 px-2 py-1 rounded text-xs">
                                            Tidak Seimbang
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('keuangan.jurnal-umum.show', $jurnal['id']) }}"
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-blue-100 text-blue-600 dark:text-white dark:bg-blue-900/20 dark:hover:bg-blue-900/30 transition-colors border border-dashed border-blue-300 dark:border-blue-700"
                                            title="Lihat Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('keuangan.jurnal-umum.edit', $jurnal['id']) }}"
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-yellow-100 text-yellow-600 dark:text-white dark:bg-yellow-900/20 dark:hover:bg-yellow-900/30 transition-colors border border-dashed border-yellow-300 dark:border-yellow-700"
                                            title="Edit Jurnal">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <button type="button" onclick="confirmDelete('{{ $jurnal['id'] }}')"
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-red-100 text-red-600 dark:text-white dark:bg-red-900/20 dark:hover:bg-red-900/30 transition-colors border border-dashed border-red-300 dark:border-red-700"
                                            title="Hapus Jurnal">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                        <form id="delete-form-{{ $jurnal['id'] }}"
                                            action="{{ route('keuangan.jurnal-umum.destroy', $jurnal['id']) }}"
                                            method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-10 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-10 w-10 text-gray-400 dark:text-gray-500 mb-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="text-gray-500 dark:text-gray-400 text-lg font-medium">Tidak ada
                                            data jurnal umum</span>
                                        <p class="text-gray-400 dark:text-gray-500 mt-1 max-w-md text-center">
                                            Tidak ada data jurnal umum yang ditemukan. Cobalah mengubah filter pencarian
                                            atau tambahkan jurnal baru.
                                        </p>
                                        <a href="{{ route('keuangan.jurnal-umum.create') }}"
                                            class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Tambah Jurnal Baru
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0 text-sm text-gray-600 dark:text-gray-400">
                        Menampilkan {{ $jurnals->firstItem() ?? 0 }} hingga {{ $jurnals->lastItem() ?? 0 }} dari
                        {{ $jurnals->total() }} entri jurnal
                    </div>
                    <div>
                        {{ $jurnals->appends(request()->query())->links('vendor.pagination.tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            if (confirm(
                    'Apakah Anda yakin ingin menghapus jurnal ini? Tindakan ini akan menghapus seluruh entri jurnal dengan nomor referensi yang sama dan memulihkan saldo kas dan rekening bank yang terkait.'
                )) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
</x-app-layout>
