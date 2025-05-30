<x-app-layout>
    <div class="max-w-full mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl overflow-hidden">
            <div
                class="px-6 py-5 bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center">
                <div>
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-10 w-1 rounded-full mr-3">
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Riwayat Pembayaran Invoice</h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Riwayat pembayaran untuk Invoice: <span
                                    class="font-semibold text-primary-600 dark:text-primary-400">{{ $invoice->nomor }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('keuangan.piutang-usaha.show', $invoice->id) }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Detail Invoice
                    </a>
                </div>
            </div>

            <div class="p-6 space-y-8">
                {{-- Invoice Information --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4 border-b pb-2">
                            Informasi Invoice</h3>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Invoice</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $invoice->nomor }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Invoice</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ date('d F Y', strtotime($invoice->tanggal)) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Customer</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $invoice->customer->nama ?? 'N/A' }}
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor SO</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $invoice->salesOrder ? $invoice->salesOrder->nomor : '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jatuh Tempo</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $invoice->jatuh_tempo ? date('d F Y', strtotime($invoice->jatuh_tempo)) : '-' }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Financial Summary for Invoice --}}
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4 border-b pb-2">
                            Ringkasan Keuangan Invoice</h3>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Invoice</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">Rp
                                    {{ number_format($invoice->total, 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pembayaran
                                    Diterima (Invoice Ini)
                                </dt>
                                <dd class="text-sm text-green-600 dark:text-green-400">Rp
                                    {{-- {{ number_format($payments, 0, ',', '.') }}</dd> --}}
                                    {{ number_format($payments->sum('jumlah'), 0, ',', '.') }}
                            </div>
                            <div class="flex justify-between border-t pt-3 mt-3">
                                <dt class="text-base font-semibold text-gray-700 dark:text-gray-200">Sisa Piutang
                                    (Invoice Ini)</dt>
                                <dd
                                    class="text-base font-semibold {{ $invoice->sisa_piutang > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                    Rp {{ number_format($invoice->sisa_piutang, 0, ',', '.') }}
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pembayaran
                                    Invoice</dt>
                                <dd class="text-sm font-medium">
                                    <span
                                        class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $invoice->status_pembayaran_class }}">
                                        {{ $invoice->status_display }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- Payment History for this Invoice --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3">Detail Riwayat Pembayaran
                        (Invoice Ini)
                    </h3>
                    @if ($payments->count() > 0)
                        <div class="overflow-x-auto shadow border-b border-gray-200 dark:border-gray-700 rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Tanggal</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Nomor Pembayaran</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Metode Pembayaran (Akun)</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Jumlah Dibayar</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Dicatat Oleh</th>

                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($payments as $item)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ date('d F Y', strtotime($item->tanggal)) }}</td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $item->nomor }}</td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                @if ($item->kas_id)
                                                    Kas: {{ $item->kas->nama ?? 'N/A' }}
                                                @elseif ($item->rekening_bank_id)
                                                    Bank: {{ $item->rekeningBank->nama_bank ?? 'N/A' }}
                                                    ({{ $item->rekeningBank->nomor_rekening ?? 'N/A' }})
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-right">
                                                Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $item->user->name ?? 'N/A' }}</td>

                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <td colspan="3"
                                            class="px-6 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase">
                                            Total Pembayaran Diterima (Invoice Ini)</td>
                                        <td
                                            class="px-6 py-3 text-right text-sm font-semibold text-green-600 dark:text-green-400">
                                            Rp {{ number_format($payments->sum('jumlah'), 0, ',', '.') }}</td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9.348 14.652A3.75 3.75 0 017.5 16.5a3.75 3.75 0 01-3.75-3.75S4.5 9.75 7.5 9.75s3.75 1.125 3.75 3.75a3.752 3.752 0 01-.348 1.652zM16.5 14.652A3.75 3.75 0 0114.652 16.5a3.75 3.75 0 01-3.75-3.75S11.625 9.75 14.652 9.75s3.75 1.125 3.75 3.75a3.752 3.752 0 01-.348 1.652z" />
                                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9 10.5h.008v.008H9v-.008zm6 0h.008v.008H15v-.008z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Belum Ada Pembayaran
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tidak ada riwayat pembayaran yang
                                tercatat untuk invoice ini.</p>
                        </div>
                    @endif
                </div>

                {{-- Sales Order Return History (Contextual) --}}
                @if ($invoice->salesOrder && $invoice->salesOrder->returPenjualan && $invoice->salesOrder->returPenjualan->count() > 0)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3">Riwayat Retur Terkait
                            Sales Order: {{ $invoice->salesOrder->nomor }} (Kontekstual)</h3>
                        <div class="overflow-x-auto shadow border-b border-gray-200 dark:border-gray-700 rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Tanggal Retur</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Nomor Retur</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Total Nilai Retur</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Status</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($invoice->salesOrder->returPenjualan as $retur)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ date('d F Y', strtotime($retur->tanggal)) }}</td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $retur->nomor }}</td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-right">
                                                Rp {{ number_format($retur->total_retur_value ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span
                                                    class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $retur->status_class }}">
                                                    {{ $retur->status_display }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                                <a href="{{ route('penjualan.retur.show', $retur->id) }}"
                                                    class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-blue-100 text-primary-600 dark:text-primary-400 dark:bg-primary-900/20 dark:hover:bg-primary-900/30 transition-colors border border-dashed border-primary-300"
                                                    title="Detail Retur">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                        fill="currentColor" class="w-4 h-4">
                                                        <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                                        <path fill-rule="evenodd"
                                                            d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <td colspan="2"
                                            class="px-6 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase">
                                            Total Nilai Retur (SO Ini)</td>
                                        <td
                                            class="px-6 py-3 text-right text-sm font-semibold text-yellow-600 dark:text-yellow-400">
                                            Rp
                                            {{ number_format($invoice->salesOrder->returPenjualan->sum('total_retur_value'), 0, ',', '.') }}
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                @elseif ($invoice->salesOrder)
                    <div class="text-center py-8 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Tidak Ada Riwayat Retur
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tidak ada retur yang tercatat untuk
                            Sales Order terkait ({{ $invoice->salesOrder->nomor }}).</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
