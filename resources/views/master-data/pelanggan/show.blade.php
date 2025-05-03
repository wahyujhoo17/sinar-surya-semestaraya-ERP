<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="max-w-4xl mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Pelanggan</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Informasi lengkap untuk <span
                        class="font-medium">{{ $customer->nama }}</span>.</p>
            </div>
            <div class="flex space-x-3 flex-shrink-0">
                {{-- Edit Button --}}
                <button
                    @click="window.dispatchEvent(new CustomEvent('open-pelanggan-modal', {detail: {mode: 'edit', customer: {{ json_encode($customer) }} }}))"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-150 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Edit
                </button>
                {{-- Back Button --}}
                <a href="{{ route('master.pelanggan.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-150 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        {{-- Main Details Card --}}
        <div
            class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
            {{-- Card Header --}}
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-primary-100 to-blue-100 dark:from-primary-900/30 dark:to-blue-900/30 p-3 rounded-lg shadow-sm">
                            {{-- Icon Customer --}}
                            <svg class="h-8 w-8 text-primary-600 dark:text-primary-400"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $customer->nama }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Kode: {{ $customer->kode }}</p>
                        </div>
                    </div>
                    {{-- Status Badge --}}
                    <div class="flex-shrink-0 mt-2 sm:mt-0">
                        @if ($customer->is_active)
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 ring-1 ring-inset ring-green-600/20 dark:ring-green-500/30">
                                <svg class="h-4 w-4 mr-1.5 animate-pulse" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                Aktif
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 ring-1 ring-inset ring-red-600/20 dark:ring-red-500/30">
                                <svg class="h-4 w-4 mr-1.5" fill="currentColor" viewBox="0 0 8 8">
                                    <path
                                        d="M4 0a4 4 0 0 0-4 4 4 4 0 0 0 4 4 4 4 0 0 0 4-4 4 4 0 0 0-4-4zM2.5 2.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-.5zm0 3a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-.5z" />
                                </svg>
                                Nonaktif
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Card Body - Details --}}
            <div class="p-6">
                <dl class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-8">
                    {{-- Kontak Section --}}
                    <div class="md:col-span-1 space-y-4">
                        <div class="flex items-center">
                            <div
                                class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Telepon</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ $customer->telepon ?: '-' }}</dd>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div
                                class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ $customer->email ?: '-' }}</dd>
                            </div>
                        </div>
                    </div>

                    {{-- Alamat Section --}}
                    <div class="md:col-span-1 space-y-4">
                        <div class="flex items-start">
                            <div
                                class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">
                                    {{ $customer->jalan ? $customer->jalan . ',' : '' }}
                                    {{ $customer->kota ? $customer->kota . ',' : '' }}
                                    {{ $customer->provinsi ? $customer->provinsi : '' }}
                                    {{ $customer->kode_pos ? ' ' . $customer->kode_pos : '' }}
                                    {{ $customer->negara ? ', ' . $customer->negara : '' }}
                                    @if (!$customer->jalan && !$customer->kota && !$customer->provinsi && !$customer->kode_pos && !$customer->negara)
                                        -
                                    @endif
                                </dd>
                            </div>
                        </div>
                    </div>

                    {{-- Detail Bisnis Section --}}
                    <div class="md:col-span-1 space-y-4">
                        <div class="flex items-center">
                            <div
                                class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 text-yellow-600 dark:text-yellow-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5.5M11 3V1.75A1.75 1.75 0 009.25 0h-3.5A1.75 1.75 0 004 1.75V3m7 0h-7" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Perusahaan</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ $customer->company ?: '-' }}</dd>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div
                                class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 text-yellow-600 dark:text-yellow-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipe</dt>
                                <dd class="text-sm text-gray-900 dark:text-white">{{ $customer->tipe ?: '-' }}</dd>
                            </div>
                        </div>
                    </div>

                    {{-- Catatan Section --}}
                    <div class="md:col-span-3">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Catatan</dt>
                        <dd
                            class="text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                            {!! nl2br(e($customer->catatan ?: 'Tidak ada catatan.')) !!}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Riwayat Transaksi Section --}}
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Riwayat Transaksi</h2>

            <!-- Quotations Table -->
            <div
                class="mb-8 bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Quotation</h3>
                </div>
                <div class="p-6">
                    @if ($customer->quotations->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                            No. Quotation</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                            Tanggal</th>
                                        <th
                                            class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                            Total</th>
                                        <th
                                            class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                            Status</th>
                                        <th
                                            class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($customer->quotations as $quotation)
                                        <tr>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $quotation->nomor_quotation }}</td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($quotation->tanggal_quotation)->format('d/m/Y') }}
                                            </td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                                                Rp
                                                {{ number_format($quotation->total_harga_setelah_diskon_pajak ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                    {{ $quotation->status ?? 'Draft' }} {{-- Adjust status logic as needed --}}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium">
                                                <a href="{{ route('penjualan.quotation.show', $quotation->id) }}"
                                                    class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-gray-500 dark:text-gray-400">
                            <div class="flex justify-center items-center mb-3">
                                <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6 text-gray-400 dark:text-gray-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-sm font-medium">Belum ada riwayat quotation</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sales Orders Table -->
            <div
                class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Sales Order</h3>
                </div>
                <div class="p-6">
                    @if ($customer->salesOrders->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                            No. SO</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                            Tanggal</th>
                                        <th
                                            class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                            Total</th>
                                        <th
                                            class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                            Status</th>
                                        <th
                                            class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($customer->salesOrders as $salesOrder)
                                        <tr>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $salesOrder->nomor_so }}</td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($salesOrder->tanggal_so)->format('d/m/Y') }}
                                            </td>
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                                                Rp
                                                {{ number_format($salesOrder->total_harga_setelah_diskon_pajak ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                                    {{ $salesOrder->status ?? 'Open' }} {{-- Adjust status logic as needed --}}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium">
                                                <a href="{{ route('penjualan.sales-order.show', $salesOrder->id) }}"
                                                    class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-gray-500 dark:text-gray-400">
                            <div class="flex justify-center items-center mb-3">
                                <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6 text-gray-400 dark:text-gray-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-sm font-medium">Belum ada riwayat sales order</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Include Modal Pelanggan Component for Edit --}}
    <x-modal-pelanggan />
</x-app-layout>
