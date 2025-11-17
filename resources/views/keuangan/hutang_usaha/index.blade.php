<x-app-layout>
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Modern Header with Stats --}}
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-10 w-1 rounded-full mr-3">
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Hutang Usaha</h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Kelola hutang usaha dan riwayat pembayaran ke supplier.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Dashboard Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                {{-- Total Hutang Card --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 p-3.5">
                                <svg class="h-7 w-7 text-indigo-500 dark:text-indigo-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Total Hutang Usaha</p>
                                <div class="mt-1 flex items-baseline">
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">Rp
                                        {{ number_format($purchaseOrders->sum('sisa_hutang'), 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PO Belum Lunas Card --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 p-3.5">
                                <svg class="h-7 w-7 text-yellow-500 dark:text-yellow-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Jumlah PO Belum Lunas</p>
                                <div class="mt-1 flex items-baseline">
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                        {{ $purchaseOrders->where('status_pembayaran', '!=', 'lunas')->count() }}</p>
                                    <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">PO</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Jatuh Tempo Card --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 p-3.5">
                                <svg class="h-7 w-7 text-emerald-500 dark:text-emerald-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Jatuh Tempo 7 Hari</p>
                                <div class="mt-1 flex items-baseline">
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                        {{ $purchaseOrders->where('jatuh_tempo', '<=', date('Y-m-d', strtotime('+7 days')))->where('status_pembayaran', '!=', 'lunas')->count() }}
                                    </p>
                                    <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">PO</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div x-data="hutangTableManager()"
            class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700">
            {{-- Enhanced Filter Section --}}
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-4">{{ __('Filter Hutang Usaha') }}
                </h3>
                <form action="{{ route('keuangan.hutang-usaha.index') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="supplier_id"
                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Supplier</label>
                            <select name="supplier_id" id="supplier_id"
                                class="pl-3 pr-8 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors w-full select2-basic">
                                <option value="">-- Semua Supplier --</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" data-name="{{ $supplier->nama }}"
                                        {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="start_date"
                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal
                                Mulai</label>
                            <input id="start_date" type="date"
                                class="pl-3 pr-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors w-full"
                                name="start_date" value="{{ request('start_date') }}">
                        </div>
                        <div>
                            <label for="end_date"
                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal
                                Akhir</label>
                            <input id="end_date" type="date"
                                class="pl-3 pr-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors w-full"
                                name="end_date" value="{{ request('end_date') }}">
                        </div>
                        <div>
                            <label for="status"
                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Status
                                Pembayaran</label>
                            <select name="status" id="status"
                                class="pl-3 pr-8 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors w-full">
                                <option value="">-- Semua Status --</option>
                                <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>
                                    Belum Bayar</option>
                                <option value="sebagian" {{ request('status') == 'sebagian' ? 'selected' : '' }}>
                                    Sebagian</option>
                                <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas
                                </option>
                            </select>
                        </div>
                        <div class="md:col-span-4 flex items-center justify-end space-x-2 mt-2">
                            @if (request()->has('supplier_id') ||
                                    request()->has('start_date') ||
                                    request()->has('end_date') ||
                                    request()->has('status'))
                                <a href="{{ route('keuangan.hutang-usaha.index') }}"
                                    class="px-3 py-1.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-xs font-medium rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-gray-500 transition-all duration-200 shadow-sm">
                                    Reset
                                </a>
                            @endif
                            <button type="submit"
                                class="px-4 py-2 bg-primary-600 border border-transparent rounded-lg text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-1 transition-colors duration-200 dark:bg-primary-700 dark:hover:bg-primary-600">
                                <span class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                    </svg>
                                    <span>Filter</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Table Container --}}
            <div class="relative px-1 sm:px-3 pb-6">
                {{-- Table Header with Search and Actions --}}
                <div class="py-4 px-5 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3 sm:mb-0">
                            {{ __('Daftar Hutang Usaha') }}</h3>
                        <div
                            class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-auto">
                            <div class="relative w-full sm:w-auto">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" id="tableSearch" placeholder="Cari data..."
                                    class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 block w-full sm:w-60">
                            </div>
                            <a href="{{ route('keuangan.hutang-usaha.export', request()->all()) }}"
                                class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 w-full sm:w-auto justify-center">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                {{ __('Export Excel') }}
                            </a>
                            <a href="{{ route('keuangan.hutang-usaha.pdf', request()->all()) }}"
                                class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 w-full sm:w-auto justify-center">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                                {{ __('Export PDF') }}
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Table Content --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="hutangTable">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600/50"
                                    onclick="sortTable(0)">
                                    {{ __('No') }}
                                    <span class="sort-icon ml-1">⇅</span>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600/50"
                                    onclick="sortTable(1)">
                                    {{ __('Nomor PO') }}
                                    <span class="sort-icon ml-1">⇅</span>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600/50"
                                    onclick="sortTable(2)">
                                    {{ __('Tanggal') }}
                                    <span class="sort-icon ml-1">⇅</span>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600/50"
                                    onclick="sortTable(3)">
                                    {{ __('Supplier') }}
                                    <span class="sort-icon ml-1">⇅</span>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600/50"
                                    onclick="sortTable(4)">
                                    {{ __('Total PO') }}
                                    <span class="sort-icon ml-1">⇅</span>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600/50"
                                    onclick="sortTable(5)">
                                    {{ __('Total Pembayaran') }}
                                    <span class="sort-icon ml-1">⇅</span>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600/50"
                                    onclick="sortTable(6)">
                                    {{ __('Total Retur') }}
                                    <span class="sort-icon ml-1">⇅</span>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600/50"
                                    onclick="sortTable(7)">
                                    {{ __('Sisa Hutang') }}
                                    <span class="sort-icon ml-1">⇅</span>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600/50"
                                    onclick="sortTable(8)">
                                    {{ __('Jatuh Tempo') }}
                                    <span class="sort-icon ml-1">⇅</span>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600/50"
                                    onclick="sortTable(9)">
                                    {{ __('Status') }}
                                    <span class="sort-icon ml-1">⇅</span>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Aksi') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($purchaseOrders as $index => $po)
                                <tr
                                    class="{{ $index % 2 == 0 ? '' : 'bg-gray-50 dark:bg-gray-800/50' }} hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $index + 1 }}</td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300">
                                        <a href="{{ route('keuangan.hutang-usaha.show', $po->id) }}"
                                            class="hover:underline">{{ $po->nomor }}</a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ date('d/m/Y', strtotime($po->tanggal)) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ $po->supplier->nama }}</td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200 text-right">
                                        {{ number_format($po->total, 0, ',', '.') }}</td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200 text-right">
                                        {{ number_format($po->total_pembayaran, 0, ',', '.') }}</td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200 text-right">
                                        {{ number_format($po->total_retur, 0, ',', '.') }}</td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right {{ $po->sisa_hutang > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                        {{ number_format($po->sisa_hutang, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        @if (isset($po->jatuh_tempo))
                                            @php
                                                $jatuhtempo = \Carbon\Carbon::parse($po->jatuh_tempo);
                                                $today = \Carbon\Carbon::now();
                                                $isOverdue =
                                                    $jatuhtempo->lt($today) && $po->status_pembayaran != 'lunas';
                                                $almostDue =
                                                    $jatuhtempo->diffInDays($today) <= 7 &&
                                                    $jatuhtempo->gt($today) &&
                                                    $po->status_pembayaran != 'lunas';
                                            @endphp
                                            <span
                                                class="{{ $isOverdue ? 'text-red-600 dark:text-red-400 font-bold' : ($almostDue ? 'text-yellow-600 dark:text-yellow-400 font-semibold' : 'text-gray-600 dark:text-gray-400') }}">
                                                {{ $jatuhtempo->format('d/m/Y') }}
                                                @if ($isOverdue)
                                                    <span class="block text-xs text-red-600 dark:text-red-400">(Telat
                                                        {{ $jatuhtempo->diffInDays($today) }} hari)</span>
                                                @elseif($almostDue)
                                                    <span
                                                        class="block text-xs text-yellow-600 dark:text-yellow-400">({{ $jatuhtempo->diffInDays($today) }}
                                                        hari lagi)</span>
                                                @endif
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        @if ($po->status_pembayaran == 'belum_bayar')
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                                {{ __('Belum Bayar') }}
                                            </span>
                                        @elseif($po->status_pembayaran == 'sebagian')
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                                                {{ __('Sebagian') }}
                                            </span>
                                        @else
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                                {{ __('Lunas') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        <div class="flex justify-center space-x-2">
                                            <a href="{{ route('keuangan.hutang-usaha.show', $po->id) }}"
                                                class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-blue-100 text-primary-600 dark:text-primary-400 dark:bg-primary-900/20 dark:hover:bg-primary-900/30 transition-colors border border-dashed border-primary-300"
                                                title="Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor" class="w-4 h-4">
                                                    <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                                    <path fill-rule="evenodd"
                                                        d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                            @if ($po->status_pembayaran != 'lunas')
                                                <a href="{{ route('keuangan.pembayaran-hutang.create', ['po_id' => $po->id]) }}"
                                                    class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-green-100 text-green-600 dark:text-green-400 dark:bg-green-900/20 dark:hover:bg-green-900/30 transition-colors border border-dashed border-green-300"
                                                    title="Bayar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                        fill="currentColor" class="w-4 h-4">
                                                        <path
                                                            d="M1 4.25a3.733 3.733 0 012.25-.75h13.5c.844 0 1.623.279 2.25.75A2.25 2.25 0 0016.75 2H3.25A2.25 2.25 0 001 4.25zM1 7.25a3.733 3.733 0 012.25-.75h13.5c.844 0 1.623.279 2.25.75A2.25 2.25 0 0016.75 5H3.25A2.25 2.25 0 001 7.25zM7 8a1 1 0 011 1 2 2 0 104 0 1 1 0 011-1h3.75A2.25 2.25 0 0119 10.25v5.5A2.25 2.25 0 0116.75 18H3.25A2.25 2.25 0 011 15.75v-5.5A2.25 2.25 0 013.25 8H7z" />
                                                    </svg>
                                                </a>
                                            @endif
                                            <a href="{{ route('keuangan.hutang-usaha.history', $po->id) }}"
                                                class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-blue-100 text-blue-600 dark:text-blue-400 dark:bg-blue-900/20 dark:hover:bg-blue-900/30 transition-colors border border-dashed border-blue-300"
                                                title="Riwayat">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor" class="w-4 h-4">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11"
                                        class="px-6 py-10 whitespace-nowrap text-center text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="h-10 w-10 text-gray-400 dark:text-gray-500 mb-2"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p>{{ __('Tidak ada data hutang usaha') }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Gunakan filter di
                                                atas untuk mencari data</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th colspan="7"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Total Sisa Hutang:') }}</th>
                                <th
                                    class="px-6 py-3 text-right text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">
                                    {{ number_format($purchaseOrders->sum('sisa_hutang'), 0, ',', '.') }}</th>
                                <th colspan="3"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Pagination if applicable -->
                @if (isset($purchaseOrders) && method_exists($purchaseOrders, 'links'))
                    <div
                        class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700">
                        {{ $purchaseOrders->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
        <!-- Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            /* Select2 Dark Mode & Tailwind Integration */
            .select2-container--default .select2-selection--single,
            .select2-container--default .select2-results__option {
                font-size: 0.95rem !important;
            }

            .select2-container--default .select2-selection--single {
                height: 42px !important;
                border: 1px solid #d1d5db !important;
                border-radius: 0.5rem !important;
                background-color: white !important;
                padding: 0 12px !important;
                display: flex !important;
                align-items: center !important;
            }

            .dark .select2-container--default .select2-selection--single {
                background-color: rgb(55 65 81) !important;
                border-color: rgb(75 85 99) !important;
                color: white !important;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 42px !important;
                padding-left: 0 !important;
                color: rgb(17 24 39) !important;
            }

            .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: white !important;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 40px !important;
                right: 12px !important;
            }

            .select2-dropdown {
                border: 1px solid #d1d5db !important;
                border-radius: 0.5rem !important;
                background-color: white !important;
            }

            .dark .select2-dropdown {
                background-color: rgb(55 65 81) !important;
                border-color: rgb(75 85 99) !important;
            }

            .select2-container--default .select2-results__option {
                color: rgb(17 24 39) !important;
                padding: 8px 12px !important;
            }

            .dark .select2-container--default .select2-results__option {
                color: white !important;
            }

            .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background-color: rgb(59 130 246) !important;
                color: white !important;
            }

            .select2-container--default .select2-search--dropdown .select2-search__field {
                border: 1px solid #d1d5db !important;
                border-radius: 0.375rem !important;
                padding: 8px 12px !important;
                background-color: white !important;
                color: rgb(17 24 39) !important;
            }

            .dark .select2-container--default .select2-search--dropdown .select2-search__field {
                background-color: rgb(75 85 99) !important;
                border-color: rgb(107 114 128) !important;
                color: white !important;
            }

            .select2-container--default .select2-selection--single:focus {
                border-color: rgb(59 130 246) !important;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
            }
        </style>
    @endpush
    @push('scripts')
        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                function formatSupplierOption(supplier) {
                    if (!supplier.id) {
                        return supplier.text;
                    }
                    var $supplier = $(supplier.element);
                    var name = $supplier.data('name') || '';
                    return $(
                        '<div class="flex flex-col py-1">' +
                        '<div class="font-medium text-gray-900 dark:text-white">' + name + '</div>' +
                        '</div>'
                    );
                }

                function formatSupplierSelection(supplier) {
                    if (!supplier.id) {
                        return '-- Semua Supplier --';
                    }
                    var $supplier = $(supplier.element);
                    var name = $supplier.data('name') || '';
                    return name;
                }
                $('#supplier_id').select2({
                    placeholder: '-- Semua Supplier --',
                    allowClear: true,
                    width: '100%',
                    templateResult: formatSupplierOption,
                    templateSelection: formatSupplierSelection,
                    escapeMarkup: function(markup) {
                        return markup;
                    }
                });
            });
        </script>
        <script>
            function hutangTableManager() {
                return {
                    // Add Alpine.js functionality here if needed
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('tableSearch');
                const table = document.getElementById('hutangTable');
                const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

                // Table search functionality
                searchInput.addEventListener('keyup', function() {
                    const searchTerm = searchInput.value.toLowerCase();

                    for (let i = 0; i < rows.length; i++) {
                        const row = rows[i];
                        const cells = row.getElementsByTagName('td');
                        let found = false;

                        for (let j = 0; j < cells.length; j++) {
                            const cellText = cells[j].textContent.toLowerCase();
                            if (cellText.indexOf(searchTerm) > -1) {
                                found = true;
                                break;
                            }
                        }

                        row.style.display = found ? '' : 'none';
                    }
                });
            });

            // Table sorting functionality
            function sortTable(colIndex) {
                const table = document.getElementById('hutangTable');
                const tbody = table.getElementsByTagName('tbody')[0];
                const rows = Array.from(tbody.getElementsByTagName('tr'));
                const sortDirection = table.getAttribute('data-sort-dir') === 'asc' ? 'desc' : 'asc';

                table.setAttribute('data-sort-dir', sortDirection);
                table.setAttribute('data-sort-col', colIndex);

                // Reset all sort icons
                document.querySelectorAll('.sort-icon').forEach(icon => {
                    icon.textContent = '⇅';
                });

                // Update the clicked column's sort icon
                const headers = table.getElementsByTagName('th');
                headers[colIndex].querySelector('.sort-icon').textContent = sortDirection === 'asc' ? '↓' : '↑';

                // Sort the rows
                rows.sort((a, b) => {
                    let aValue = a.getElementsByTagName('td')[colIndex].textContent.trim();
                    let bValue = b.getElementsByTagName('td')[colIndex].textContent.trim();

                    // Check if values are numbers (amounts)
                    if (colIndex >= 4 && colIndex <= 7) {
                        aValue = parseFloat(aValue.replace(/\./g, '').replace(',', '.')) || 0;
                        bValue = parseFloat(bValue.replace(/\./g, '').replace(',', '.')) || 0;
                    } else if (colIndex === 2) {
                        // Handle date sorting (assuming DD/MM/YYYY format)
                        const aParts = aValue.split('/');
                        const bParts = bValue.split('/');
                        if (aParts.length === 3 && bParts.length === 3) {
                            aValue = new Date(aParts[2], aParts[1] - 1, aParts[0]);
                            bValue = new Date(bParts[2], bParts[1] - 1, bParts[0]);
                        }
                    }

                    if (aValue === bValue) return 0;

                    if (sortDirection === 'asc') {
                        return aValue > bValue ? 1 : -1;
                    } else {
                        return aValue < bValue ? 1 : -1;
                    }
                });

                // Re-append rows to tbody in sorted order
                rows.forEach(row => tbody.appendChild(row));
            }
        </script>
    @endpush
</x-app-layout>
