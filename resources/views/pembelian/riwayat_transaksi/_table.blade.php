{{-- Table content for AJAX reloading --}}
<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
    <thead class="bg-gray-50 dark:bg-gray-800">
        <tr>
            <th scope="col" class="px-6 py-3 text-left">
                <button type="button" @click="sort('nomor')"
                    class="group flex items-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    No. PO
                    <span class="ml-1 flex-none rounded text-gray-400 group-hover:visible group-focus:visible">
                        <template x-if="sortColumn === 'nomor' && sortDirection === 'asc'">
                            <svg class="h-5 w-5 text-primary-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template x-if="sortColumn === 'nomor' && sortDirection === 'desc'">
                            <svg class="h-5 w-5 text-primary-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template x-if="sortColumn !== 'nomor'">
                            <svg class="h-5 w-5 text-gray-400 invisible group-hover:visible" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 3a1 1 0 01.707.293l4 4a1 1 0 01-1.414 1.414L10 5.414 6.707 8.707a1 1 0 01-1.414-1.414l4-4A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </template>
                    </span>
                </button>
            </th>
            <th scope="col" class="px-6 py-3 text-left">
                <button type="button" @click="sort('tanggal')"
                    class="group flex items-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Tanggal
                    <span class="ml-1 flex-none rounded text-gray-400 group-hover:visible group-focus:visible">
                        <template x-if="sortColumn === 'tanggal' && sortDirection === 'asc'">
                            <svg class="h-5 w-5 text-primary-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template x-if="sortColumn === 'tanggal' && sortDirection === 'desc'">
                            <svg class="h-5 w-5 text-primary-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template x-if="sortColumn !== 'tanggal'">
                            <svg class="h-5 w-5 text-gray-400 invisible group-hover:visible" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 3a1 1 0 01.707.293l4 4a1 1 0 01-1.414 1.414L10 5.414 6.707 8.707a1 1 0 01-1.414-1.414l4-4A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </template>
                    </span>
                </button>
            </th>
            <th scope="col" class="px-6 py-3 text-left">
                <button type="button" @click="sort('supplier_id')"
                    class="group flex items-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Supplier
                    <span class="ml-1 flex-none rounded text-gray-400 group-hover:visible group-focus:visible">
                        <template x-if="sortColumn === 'supplier_id' && sortDirection === 'asc'">
                            <svg class="h-5 w-5 text-primary-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template x-if="sortColumn === 'supplier_id' && sortDirection === 'desc'">
                            <svg class="h-5 w-5 text-primary-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template x-if="sortColumn !== 'supplier_id'">
                            <svg class="h-5 w-5 text-gray-400 invisible group-hover:visible" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 3a1 1 0 01.707.293l4 4a1 1 0 01-1.414 1.414L10 5.414 6.707 8.707a1 1 0 01-1.414-1.414l4-4A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </template>
                    </span>
                </button>
            </th>
            <th scope="col" class="px-6 py-3 text-left">
                <button type="button" @click="sort('total')"
                    class="group flex items-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Total
                    <span class="ml-1 flex-none rounded text-gray-400 group-hover:visible group-focus:visible">
                        <template x-if="sortColumn === 'total' && sortDirection === 'asc'">
                            <svg class="h-5 w-5 text-primary-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template x-if="sortColumn === 'total' && sortDirection === 'desc'">
                            <svg class="h-5 w-5 text-primary-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template x-if="sortColumn !== 'total'">
                            <svg class="h-5 w-5 text-gray-400 invisible group-hover:visible" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 3a1 1 0 01.707.293l4 4a1 1 0 01-1.414 1.414L10 5.414 6.707 8.707a1 1 0 01-1.414-1.414l4-4A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </template>
                    </span>
                </button>
            </th>
            <th scope="col" class="px-6 py-3 text-left">
                <button type="button" @click="sort('status')"
                    class="group flex items-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Status
                    <span class="ml-1 flex-none rounded text-gray-400 group-hover:visible group-focus:visible">
                        <template x-if="sortColumn === 'status' && sortDirection === 'asc'">
                            <svg class="h-5 w-5 text-primary-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template x-if="sortColumn === 'status' && sortDirection === 'desc'">
                            <svg class="h-5 w-5 text-primary-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template x-if="sortColumn !== 'status'">
                            <svg class="h-5 w-5 text-gray-400 invisible group-hover:visible" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 3a1 1 0 01.707.293l4 4a1 1 0 01-1.414 1.414L10 5.414 6.707 8.707a1 1 0 01-1.414-1.414l4-4A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </template>
                    </span>
                </button>
            </th>
            <th scope="col" class="px-6 py-3 text-left">
                <button type="button" @click="sort('status_pembayaran')"
                    class="group flex items-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Pembayaran
                    <span class="ml-1 flex-none rounded text-gray-400 group-hover:visible group-focus:visible">
                        <template x-if="sortColumn === 'status_pembayaran' && sortDirection === 'asc'">
                            <svg class="h-5 w-5 text-primary-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template x-if="sortColumn === 'status_pembayaran' && sortDirection === 'desc'">
                            <svg class="h-5 w-5 text-primary-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template x-if="sortColumn !== 'status_pembayaran'">
                            <svg class="h-5 w-5 text-gray-400 invisible group-hover:visible" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 3a1 1 0 01.707.293l4 4a1 1 0 01-1.414 1.414L10 5.414 6.707 8.707a1 1 0 01-1.414-1.414l4-4A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </template>
                    </span>
                </button>
            </th>
            <th scope="col"
                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Aksi
            </th>
        </tr>
    </thead>
    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
        @forelse ($transactions as $transaction)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                    {{ $transaction->nomor }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                    {{ \Carbon\Carbon::parse($transaction->tanggal)->format('d M Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                    {{ $transaction->supplier->nama ?? 'N/A' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                    Rp {{ number_format($transaction->total, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @php
                        $statusColor = 'gray';
                        switch ($transaction->status) {
                            case 'draft':
                                $statusColor = 'gray';
                                break;
                            case 'diproses':
                                $statusColor = 'blue';
                                break;
                            case 'dikirim':
                                $statusColor = 'amber';
                                break;
                            case 'selesai':
                                $statusColor = 'emerald';
                                break;
                            case 'dibatalkan':
                                $statusColor = 'red';
                                break;
                        }
                    @endphp
                    <span
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 dark:bg-{{ $statusColor }}-800 dark:bg-opacity-30 dark:text-{{ $statusColor }}-400">
                        {{ ucfirst($transaction->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @php
                        $paymentStatusColor = 'gray';
                        $paymentStatusName = $transaction->status_pembayaran;

                        switch ($transaction->status_pembayaran) {
                            case 'belum_bayar':
                                $paymentStatusColor = 'red';
                                $paymentStatusName = 'Belum Bayar';
                                break;
                            case 'sebagian':
                                $paymentStatusColor = 'amber';
                                $paymentStatusName = 'Sebagian';
                                break;
                            case 'lunas':
                                $paymentStatusColor = 'emerald';
                                $paymentStatusName = 'Lunas';
                                break;
                        }
                    @endphp
                    <span
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $paymentStatusColor }}-100 text-{{ $paymentStatusColor }}-800 dark:bg-{{ $paymentStatusColor }}-800 dark:bg-opacity-30 dark:text-{{ $paymentStatusColor }}-400">
                        {{ $paymentStatusName }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="{{ route('pembelian.riwayat-transaksi.show', $transaction->id) }}"
                        class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300">
                        Detail
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                    <div class="py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <h3 class="mt-2 text-base font-medium text-gray-900 dark:text-gray-200">Tidak ada transaksi
                            ditemukan</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Tidak ada transaksi pembelian yang sesuai dengan filter yang dipilih.
                        </p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
