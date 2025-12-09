<!DOCTYPE html>
<html>

<head>
    <title>
        {{ $reportType === 'balance_sheet' ? 'Neraca' : ($reportType === 'income_statement' ? 'Laporan Laba Rugi' : 'Laporan Arus Kas') }}
    </title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .bg-gray {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    <h1>{{ $reportType === 'balance_sheet' ? 'NERACA' : ($reportType === 'income_statement' ? 'LAPORAN LABA RUGI' : 'LAPORAN ARUS KAS') }}
    </h1>

    @if ($reportType === 'balance_sheet')
        <p><strong>Per tanggal:</strong> {{ $tanggalAkhir->format('d F Y') }}</p>
    @else
        <p><strong>Periode:</strong> {{ $tanggalAwal->format('d F Y') }} - {{ $tanggalAkhir->format('d F Y') }}</p>
    @endif

    @if ($reportType === 'balance_sheet')
        {{-- NERACA --}}
        <table>
            <thead>
                <tr>
                    <th style="width: 15%">Kode</th>
                    <th style="width: 65%">Nama Akun</th>
                    <th style="width: 20%">Saldo</th>
                </tr>
            </thead>
            <tbody>
                {{-- ASET --}}
                <tr class="bg-gray">
                    <td colspan="3" class="font-bold">ASET</td>
                </tr>
                @if (isset($data['assets_grouped']) && isset($data['assets_grouped']['groups']))
                    @foreach ($data['assets_grouped']['groups'] as $group)
                        <tr class="font-bold bg-gray">
                            <td colspan="3">{{ $group['name'] }}</td>
                        </tr>
                        @foreach ($group['accounts'] as $asset)
                            <tr>
                                <td>{{ $asset['kode'] }}</td>
                                <td>{{ $asset['nama'] }}</td>
                                <td class="text-right">{{ number_format($asset['balance'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="font-bold">
                            <td colspan="2">Subtotal {{ $group['name'] }}</td>
                            <td class="text-right">{{ number_format($group['subtotal'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @else
                    @foreach ($data['assets'] as $asset)
                        <tr>
                            <td>{{ $asset['kode'] }}</td>
                            <td>{{ $asset['nama'] }}</td>
                            <td class="text-right">{{ number_format($asset['balance'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @endif
                <tr class="font-bold">
                    <td colspan="2">TOTAL ASET</td>
                    <td class="text-right">{{ number_format($data['totals']['total_assets'], 0, ',', '.') }}</td>
                </tr>

                {{-- KEWAJIBAN --}}
                <tr>
                    <td colspan="3"></td>
                </tr>
                <tr class="bg-gray">
                    <td colspan="3" class="font-bold">KEWAJIBAN</td>
                </tr>
                @if (isset($data['liabilities_grouped']) && isset($data['liabilities_grouped']['groups']))
                    @foreach ($data['liabilities_grouped']['groups'] as $group)
                        <tr class="font-bold bg-gray">
                            <td colspan="3">{{ $group['name'] }}</td>
                        </tr>
                        @foreach ($group['accounts'] as $liability)
                            <tr>
                                <td>{{ $liability['kode'] }}</td>
                                <td>{{ $liability['nama'] }}</td>
                                <td class="text-right">{{ number_format($liability['balance'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="font-bold">
                            <td colspan="2">Subtotal {{ $group['name'] }}</td>
                            <td class="text-right">{{ number_format($group['subtotal'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @else
                    @foreach ($data['liabilities'] as $liability)
                        <tr>
                            <td>{{ $liability['kode'] }}</td>
                            <td>{{ $liability['nama'] }}</td>
                            <td class="text-right">{{ number_format($liability['balance'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @endif
                <tr class="font-bold">
                    <td colspan="2">TOTAL KEWAJIBAN</td>
                    <td class="text-right">{{ number_format($data['totals']['total_liabilities'], 0, ',', '.') }}</td>
                </tr>

                {{-- EKUITAS --}}
                <tr>
                    <td colspan="3"></td>
                </tr>
                <tr class="bg-gray">
                    <td colspan="3" class="font-bold">EKUITAS</td>
                </tr>
                @if (isset($data['equity_grouped']) && isset($data['equity_grouped']['groups']))
                    @foreach ($data['equity_grouped']['groups'] as $group)
                        @foreach ($group['accounts'] as $equity)
                            <tr>
                                <td>{{ $equity['kode'] }}</td>
                                <td>{{ $equity['nama'] }}</td>
                                <td class="text-right">{{ number_format($equity['balance'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                @else
                    @foreach ($data['equity'] as $equity)
                        <tr>
                            <td>{{ $equity['kode'] }}</td>
                            <td>{{ $equity['nama'] }}</td>
                            <td class="text-right">{{ number_format($equity['balance'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @endif
                <tr class="font-bold">
                    <td colspan="2">TOTAL EKUITAS</td>
                    <td class="text-right">{{ number_format($data['totals']['total_equity'], 0, ',', '.') }}</td>
                </tr>

                {{-- TOTAL KEWAJIBAN DAN EKUITAS --}}
                <tr class="font-bold">
                    <td colspan="2">TOTAL KEWAJIBAN DAN EKUITAS</td>
                    <td class="text-right">
                        {{ number_format($data['totals']['total_liabilities'] + $data['totals']['total_equity'], 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
    @elseif($reportType === 'income_statement')
        {{-- LAPORAN LABA RUGI --}}
        <table>
            <thead>
                <tr>
                    <th style="width: 65%">Keterangan</th>
                    <th style="width: 35%">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($data['revenue']))
                    {{-- PENDAPATAN --}}
                    <tr class="bg-gray">
                        <td class="font-bold">PENDAPATAN</td>
                        <td></td>
                    </tr>

                    {{-- Penjualan --}}
                    <tr>
                        <td style="padding-left: 10px;"><strong>Penjualan</strong></td>
                        <td class="text-right font-bold">
                            {{ number_format($data['revenue']['sales_revenue'] ?? 0, 0, ',', '.') }}</td>
                    </tr>

                    {{-- Pendapatan Lain --}}
                    @if (isset($data['revenue']['other_income']) && count($data['revenue']['other_income']) > 0)
                        <tr>
                            <td style="padding-left: 10px;"><strong>Pendapatan Lain</strong></td>
                            <td></td>
                        </tr>
                        @foreach ($data['revenue']['other_income'] as $income)
                            <tr>
                                <td style="padding-left: 20px;">{{ $income['nama'] }}</td>
                                <td class="text-right">{{ number_format($income['balance'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @endif

                    <tr class="font-bold">
                        <td>Total Pendapatan</td>
                        <td class="text-right">{{ number_format($data['revenue']['total_revenue'] ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>

                    {{-- HARGA POKOK PENJUALAN --}}
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr class="bg-gray">
                        <td class="font-bold">HARGA POKOK PENJUALAN</td>
                        <td></td>
                    </tr>
                    @if (isset($data['cogs']))
                        {{-- Persediaan Awal - Selalu tampilkan --}}
                        <tr>
                            <td style="padding-left: 10px;">Persediaan Awal</td>
                            <td class="text-right">
                                {{ number_format($data['cogs']['persediaan_awal_total'] ?? 0, 0, ',', '.') }}</td>
                        </tr>

                        {{-- Pembelian - Selalu tampilkan --}}
                        <tr>
                            <td style="padding-left: 10px;">Pembelian</td>
                            <td class="text-right">
                                {{ number_format($data['cogs']['pembelian_total'] ?? 0, 0, ',', '.') }}</td>
                        </tr>

                        {{-- Jumlah Persediaan - Selalu tampilkan --}}
                        <tr>
                            <td style="padding-left: 10px;">Jumlah Persediaan</td>
                            <td class="text-right">
                                {{ number_format($data['cogs']['jumlah_persediaan'] ?? 0, 0, ',', '.') }}</td>
                        </tr>

                        {{-- Persediaan Akhir - Selalu tampilkan --}}
                        <tr>
                            <td style="padding-left: 10px;">Persediaan Akhir</td>
                            <td class="text-right">
                                {{ number_format($data['cogs']['persediaan_akhir_total'] ?? 0, 0, ',', '.') }}</td>
                        </tr>

                        <tr class="font-bold">
                            <td>Harga Pokok Penjualan</td>
                            <td class="text-right">{{ number_format($data['cogs']['total_cogs'] ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endif

                    {{-- LABA KOTOR --}}
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr class="font-bold bg-gray">
                        <td>LABA KOTOR</td>
                        <td class="text-right">{{ number_format($data['totals']['gross_profit'] ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>

                    {{-- BEBAN OPERASIONAL --}}
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr class="bg-gray">
                        <td class="font-bold">BEBAN OPERASIONAL</td>
                        <td></td>
                    </tr>
                    @if (isset($data['operating_expenses']))
                        @foreach (['salary_from_journal', 'utility_expenses', 'rent_expenses', 'admin_expenses', 'transport_expenses', 'maintenance_expenses', 'marketing_expenses', 'professional_expenses', 'insurance_expenses', 'other_expenses'] as $expenseKey)
                            @if (isset($data['operating_expenses'][$expenseKey]) && count($data['operating_expenses'][$expenseKey]) > 0)
                                @foreach ($data['operating_expenses'][$expenseKey] as $expense)
                                    <tr>
                                        <td style="padding-left: 10px;">{{ $expense['nama'] }}</td>
                                        <td class="text-right">{{ number_format($expense['balance'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                        <tr class="font-bold">
                            <td>Total Beban Operasional</td>
                            <td class="text-right">
                                {{ number_format($data['operating_expenses']['total_operating_expenses'] ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endif

                    {{-- LABA BERSIH --}}
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr class="font-bold bg-gray">
                        <td>LABA BERSIH</td>
                        <td class="text-right">{{ number_format($data['totals']['net_income'] ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="2" class="text-center">Tidak ada data laporan laba rugi untuk periode ini</td>
                    </tr>
                @endif
            </tbody>
        </table>
    @else
        {{-- LAPORAN ARUS KAS --}}
        <table>
            <thead>
                <tr>
                    <th style="width: 60%">Uraian</th>
                    <th style="width: 20%">Kas Masuk</th>
                    <th style="width: 20%">Kas Keluar</th>
                </tr>
            </thead>
            <tbody>
                {{-- KAS --}}
                <tr class="bg-gray">
                    <td class="font-bold">TRANSAKSI KAS</td>
                    <td></td>
                    <td></td>
                </tr>
                @foreach ($data['kas_transactions'] as $kasTransaction)
                    <tr>
                        <td>{{ $kasTransaction->kas->nama ?? 'Kas' }}</td>
                        <td class="text-right">{{ number_format($kasTransaction->total_masuk, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($kasTransaction->total_keluar, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr class="font-bold">
                    <td>SUBTOTAL KAS</td>
                    <td class="text-right">{{ number_format($data['totals']['total_kas_masuk'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($data['totals']['total_kas_keluar'], 0, ',', '.') }}</td>
                </tr>

                {{-- BANK --}}
                <tr>
                    <td colspan="3"></td>
                </tr>
                <tr class="bg-gray">
                    <td class="font-bold">TRANSAKSI BANK</td>
                    <td></td>
                    <td></td>
                </tr>
                @foreach ($data['bank_transactions'] as $bankTransaction)
                    <tr>
                        <td>{{ $bankTransaction->rekening->nama_rekening ?? 'Bank' }}</td>
                        <td class="text-right">{{ number_format($bankTransaction->total_masuk, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($bankTransaction->total_keluar, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr class="font-bold">
                    <td>SUBTOTAL BANK</td>
                    <td class="text-right">{{ number_format($data['totals']['total_bank_masuk'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($data['totals']['total_bank_keluar'], 0, ',', '.') }}</td>
                </tr>

                {{-- TOTAL --}}
                <tr class="font-bold">
                    <td>TOTAL ARUS KAS</td>
                    <td class="text-right">{{ number_format($data['totals']['total_masuk'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($data['totals']['total_keluar'], 0, ',', '.') }}</td>
                </tr>

                <tr class="font-bold">
                    <td>ARUS KAS BERSIH</td>
                    <td colspan="2" class="text-right">
                        {{ number_format($data['totals']['total_masuk'] - $data['totals']['total_keluar'], 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
    @endif
</body>

</html>
