{{-- Transaction Detail Partial for AJAX --}}
<div class="overflow-x-auto bg-white dark:bg-gray-800">
    @if (count($bukuBesarData['transaksi']) > 0)
        <div class="p-4 bg-gray-50 dark:bg-gray-900/30 border-b border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Saldo Awal:</span>
                    <span
                        class="font-semibold {{ $bukuBesarData['opening_balance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format(abs($bukuBesarData['opening_balance']), 0, ',', '.') }}
                        {{ $bukuBesarData['opening_balance'] < 0 ? '(-)' : '' }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Total Debit:</span>
                    <span class="font-semibold text-gray-900 dark:text-white">
                        Rp {{ number_format($bukuBesarData['period_debit'], 0, ',', '.') }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Total Kredit:</span>
                    <span class="font-semibold text-gray-900 dark:text-white">
                        Rp {{ number_format($bukuBesarData['period_kredit'], 0, ',', '.') }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Saldo Akhir:</span>
                    <span
                        class="font-semibold {{ $bukuBesarData['ending_balance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format(abs($bukuBesarData['ending_balance']), 0, ',', '.') }}
                        {{ $bukuBesarData['ending_balance'] < 0 ? '(-)' : '' }}
                    </span>
                </div>
            </div>
        </div>

        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-900">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                        Tanggal
                    </th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                        No. Referensi
                    </th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                        Keterangan
                    </th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                        Debit
                    </th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                        Kredit
                    </th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                        Saldo
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                {{-- Opening Balance Row --}}
                <tr class="bg-gray-50 dark:bg-gray-900/50">
                    <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                        {{ \Carbon\Carbon::parse($tanggalAwal)->subDay()->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                        -
                    </td>
                    <td class="px-4 py-2 text-xs font-medium text-gray-700 dark:text-gray-300">
                        SALDO AWAL PERIODE
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs text-right text-gray-500 dark:text-gray-400">
                        -
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs text-right text-gray-500 dark:text-gray-400">
                        -
                    </td>
                    <td
                        class="px-4 py-2 whitespace-nowrap text-xs text-right font-medium {{ $bukuBesarData['opening_balance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format(abs($bukuBesarData['opening_balance']), 0, ',', '.') }}
                        {{ $bukuBesarData['opening_balance'] < 0 ? '(-)' : '' }}
                    </td>
                </tr>

                {{-- Transaction Rows --}}
                @foreach ($bukuBesarData['transaksi'] as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-700 dark:text-gray-300">
                            {{ \Carbon\Carbon::parse($item['transaksi']->tanggal)->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-700 dark:text-gray-300">
                            {{ $item['transaksi']->no_referensi }}
                        </td>
                        <td class="px-4 py-2 text-xs text-gray-700 dark:text-gray-300">
                            {{ $item['transaksi']->keterangan }}
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-xs text-right text-gray-700 dark:text-gray-300">
                            @if ($item['transaksi']->debit > 0)
                                Rp {{ number_format($item['transaksi']->debit, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-xs text-right text-gray-700 dark:text-gray-300">
                            @if ($item['transaksi']->kredit > 0)
                                Rp {{ number_format($item['transaksi']->kredit, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td
                            class="px-4 py-2 whitespace-nowrap text-xs text-right font-medium {{ $item['saldo'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            Rp {{ number_format(abs($item['saldo']), 0, ',', '.') }}
                            {{ $item['saldo'] < 0 ? '(-)' : '' }}
                        </td>
                    </tr>
                @endforeach

                {{-- Summary Row --}}
                <tr class="bg-gray-100 dark:bg-gray-900 font-semibold">
                    <td colspan="3" class="px-4 py-2 text-xs text-gray-900 dark:text-white">
                        TOTAL PERIODE ({{ $bukuBesarData['total_transaksi'] }} transaksi)
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs text-right text-gray-900 dark:text-white">
                        Rp {{ number_format($bukuBesarData['period_debit'], 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs text-right text-gray-900 dark:text-white">
                        Rp {{ number_format($bukuBesarData['period_kredit'], 0, ',', '.') }}
                    </td>
                    <td
                        class="px-4 py-2 whitespace-nowrap text-xs text-right font-bold {{ $bukuBesarData['ending_balance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format(abs($bukuBesarData['ending_balance']), 0, ',', '.') }}
                        {{ $bukuBesarData['ending_balance'] < 0 ? '(-)' : '' }}
                    </td>
                </tr>
            </tbody>
        </table>
    @else
        <div class="text-center py-8">
            <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <h4 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada transaksi</h4>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Tidak ada transaksi untuk akun ini pada periode yang dipilih.
            </p>
        </div>
    @endif
</div>
