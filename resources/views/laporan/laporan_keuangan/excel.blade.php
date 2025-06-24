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
                @foreach ($data['assets'] as $asset)
                    <tr>
                        <td>{{ $asset['kode'] }}</td>
                        <td>{{ $asset['nama'] }}</td>
                        <td class="text-right">{{ number_format($asset['balance'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
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
                @foreach ($data['liabilities'] as $liability)
                    <tr>
                        <td>{{ $liability['kode'] }}</td>
                        <td>{{ $liability['nama'] }}</td>
                        <td class="text-right">{{ number_format($liability['balance'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
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
                @foreach ($data['equity'] as $equity)
                    <tr>
                        <td>{{ $equity['kode'] }}</td>
                        <td>{{ $equity['nama'] }}</td>
                        <td class="text-right">{{ number_format($equity['balance'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
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
                    <th style="width: 15%">Kode</th>
                    <th style="width: 65%">Nama Akun</th>
                    <th style="width: 20%">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                {{-- PENDAPATAN --}}
                <tr class="bg-gray">
                    <td colspan="3" class="font-bold">PENDAPATAN</td>
                </tr>
                @foreach ($data['income'] as $income)
                    <tr>
                        <td>{{ $income['kode'] }}</td>
                        <td>{{ $income['nama'] }}</td>
                        <td class="text-right">{{ number_format($income['balance'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr class="font-bold">
                    <td colspan="2">TOTAL PENDAPATAN</td>
                    <td class="text-right">{{ number_format($data['totals']['total_income'], 0, ',', '.') }}</td>
                </tr>

                {{-- BEBAN --}}
                <tr>
                    <td colspan="3"></td>
                </tr>
                <tr class="bg-gray">
                    <td colspan="3" class="font-bold">BEBAN</td>
                </tr>
                @foreach ($data['expenses'] as $expense)
                    <tr>
                        <td>{{ $expense['kode'] }}</td>
                        <td>{{ $expense['nama'] }}</td>
                        <td class="text-right">{{ number_format($expense['balance'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr class="font-bold">
                    <td colspan="2">TOTAL BEBAN</td>
                    <td class="text-right">{{ number_format($data['totals']['total_expenses'], 0, ',', '.') }}</td>
                </tr>

                {{-- LABA/RUGI BERSIH --}}
                <tr class="font-bold">
                    <td colspan="2">LABA/RUGI BERSIH</td>
                    <td class="text-right">{{ number_format($data['totals']['net_income'], 0, ',', '.') }}</td>
                </tr>
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
