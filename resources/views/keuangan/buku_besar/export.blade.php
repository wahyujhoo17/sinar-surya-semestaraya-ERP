<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Buku Besar - {{ $bukuBesarData['akun']->kode }} {{ $bukuBesarData['akun']->nama }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .report-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .account-info {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border: 1px solid #dee2e6;
        }

        .account-info table {
            width: 100%;
        }

        .account-info td {
            padding: 3px 0;
        }

        .account-info .label {
            font-weight: bold;
            width: 150px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .bg-gray {
            background-color: #f8f9fa;
        }

        .summary-row {
            background-color: #e9ecef;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="company-name">{{ config('app.name', 'ERP Sinar Surya') }}</div>
        <div class="report-title">BUKU BESAR</div>
        <div>Periode: {{ \Carbon\Carbon::parse($tanggalAwal)->format('d/m/Y') }} -
            {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y') }}</div>
    </div>

    <div class="account-info">
        <table>
            <tr>
                <td class="label">Kode Akun:</td>
                <td>{{ $bukuBesarData['akun']->kode }}</td>
                <td class="label">Kategori:</td>
                <td>{{ ucfirst($bukuBesarData['akun']->kategori) }}</td>
            </tr>
            <tr>
                <td class="label">Nama Akun:</td>
                <td>{{ $bukuBesarData['akun']->nama }}</td>
                <td class="label">Saldo Akhir:</td>
                <td class="font-bold">
                    Rp {{ number_format(abs($bukuBesarData['ending_balance']), 0, ',', '.') }}
                    {{ $bukuBesarData['ending_balance'] < 0 ? '(-)' : '' }}
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th width="10%">Tanggal</th>
                <th width="15%">No. Referensi</th>
                <th width="35%">Keterangan</th>
                <th width="15%">Debit</th>
                <th width="15%">Kredit</th>
                <th width="15%">Saldo</th>
            </tr>
        </thead>
        <tbody>
            {{-- Opening Balance Row --}}
            <tr class="bg-gray">
                <td class="text-center">{{ \Carbon\Carbon::parse($tanggalAwal)->subDay()->format('d/m/Y') }}</td>
                <td class="text-center">-</td>
                <td class="font-bold">SALDO AWAL PERIODE</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-right font-bold">
                    Rp {{ number_format(abs($bukuBesarData['opening_balance']), 0, ',', '.') }}
                    {{ $bukuBesarData['opening_balance'] < 0 ? '(-)' : '' }}
                </td>
            </tr>

            {{-- Transaction Rows --}}
            @foreach ($bukuBesarData['transaksi'] as $item)
                <tr>
                    <td class="text-center">{{ \Carbon\Carbon::parse($item['transaksi']->tanggal)->format('d/m/Y') }}
                    </td>
                    <td>{{ $item['transaksi']->no_referensi }}</td>
                    <td>{{ $item['transaksi']->keterangan }}</td>
                    <td class="text-right">
                        @if ($item['transaksi']->debit > 0)
                            {{ number_format($item['transaksi']->debit, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($item['transaksi']->kredit > 0)
                            {{ number_format($item['transaksi']->kredit, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right font-bold">
                        {{ number_format(abs($item['saldo']), 0, ',', '.') }}
                        {{ $item['saldo'] < 0 ? '(-)' : '' }}
                    </td>
                </tr>
            @endforeach

            {{-- Summary Row --}}
            <tr class="summary-row">
                <td colspan="3" class="text-center font-bold">TOTAL PERIODE</td>
                <td class="text-right font-bold">{{ number_format($bukuBesarData['period_debit'], 0, ',', '.') }}</td>
                <td class="text-right font-bold">{{ number_format($bukuBesarData['period_kredit'], 0, ',', '.') }}</td>
                <td class="text-right font-bold">
                    {{ number_format(abs($bukuBesarData['ending_balance']), 0, ',', '.') }}
                    {{ $bukuBesarData['ending_balance'] < 0 ? '(-)' : '' }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <div>Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</div>
        <div>Total Transaksi: {{ number_format($bukuBesarData['total_transaksi']) }}</div>
    </div>
</body>

</html>
