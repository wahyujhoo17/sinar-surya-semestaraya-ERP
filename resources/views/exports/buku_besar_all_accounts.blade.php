<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Buku Besar Semua Akun Export</title>
</head>

<body>
    <table>
        <!-- Header Company -->
        <tr>
            <td colspan="9" style="text-align: center; font-weight: bold; font-size: 16px;">
                PT SINAR SURYA SEMESTARAYA
            </td>
        </tr>
        <tr>
            <td colspan="9" style="text-align: center; font-weight: bold; font-size: 14px;">
                BUKU BESAR - SEMUA AKUN
            </td>
        </tr>
        <tr>
            <td colspan="9" style="text-align: center; font-size: 10px;">
                Periode: {{ date('d/m/Y', strtotime($tanggalAwal)) }} - {{ date('d/m/Y', strtotime($tanggalAkhir)) }}
            </td>
        </tr>

        <!-- Empty Row -->
        <tr>
            <td colspan="9"></td>
        </tr>

        <!-- Summary by Category -->
        <tr style="background-color: #E3F2FD; font-weight: bold;">
            <td colspan="9" style="text-align: center; font-weight: bold; font-size: 12px;">
                RINGKASAN PER KATEGORI
            </td>
        </tr>

        <tr style="background-color: #4472C4; color: white; font-weight: bold;">
            <td style="border: 1px solid #000; text-align: center;">ASET</td>
            <td style="border: 1px solid #000; text-align: center;">KEWAJIBAN</td>
            <td style="border: 1px solid #000; text-align: center;">MODAL</td>
            <td style="border: 1px solid #000; text-align: center;">PENDAPATAN</td>
            <td style="border: 1px solid #000; text-align: center;">BEBAN</td>
            <td style="border: 1px solid #000; text-align: center;">LAINNYA</td>
            <td style="border: 1px solid #000; text-align: center;">TOTAL</td>
            <td colspan="2" style="border: 1px solid #000;"></td>
        </tr>

        <tr>
            <td style="border: 1px solid #ccc; text-align: right; padding-right: 5px;">
                {{ number_format($totalsByCategory['asset'], 0, ',', '.') }}
            </td>
            <td style="border: 1px solid #ccc; text-align: right; padding-right: 5px;">
                {{ number_format($totalsByCategory['liability'], 0, ',', '.') }}
            </td>
            <td style="border: 1px solid #ccc; text-align: right; padding-right: 5px;">
                {{ number_format($totalsByCategory['equity'], 0, ',', '.') }}
            </td>
            <td style="border: 1px solid #ccc; text-align: right; padding-right: 5px;">
                {{ number_format($totalsByCategory['income'], 0, ',', '.') }}
            </td>
            <td style="border: 1px solid #ccc; text-align: right; padding-right: 5px;">
                {{ number_format($totalsByCategory['expense'], 0, ',', '.') }}
            </td>
            <td style="border: 1px solid #ccc; text-align: right; padding-right: 5px;">
                {{ number_format($totalsByCategory['other'], 0, ',', '.') }}
            </td>
            <td style="border: 1px solid #ccc; text-align: right; padding-right: 5px; font-weight: bold;">
                {{ number_format($grandTotal, 0, ',', '.') }}
            </td>
            <td colspan="2" style="border: 1px solid #ccc;"></td>
        </tr>

        <!-- Empty Rows -->
        <tr>
            <td colspan="9"></td>
        </tr>
        <tr>
            <td colspan="9"></td>
        </tr>

        <!-- Detail Table Headers -->
        <tr style="background-color: #4472C4; color: white; font-weight: bold;">
            <td style="border: 1px solid #000; text-align: center;">Kode</td>
            <td style="border: 1px solid #000; text-align: center;">Nama Akun</td>
            <td style="border: 1px solid #000; text-align: center;">Kategori</td>
            <td style="border: 1px solid #000; text-align: center;">Saldo Awal</td>
            <td style="border: 1px solid #000; text-align: center;">Debit Periode</td>
            <td style="border: 1px solid #000; text-align: center;">Kredit Periode</td>
            <td style="border: 1px solid #000; text-align: center;">Mutasi</td>
            <td style="border: 1px solid #000; text-align: center;">Saldo Akhir</td>
            <td style="border: 1px solid #000; text-align: center;">Status</td>
        </tr>

        <!-- Data Rows -->
        @foreach ($accounts as $index => $accountData)
            <tr style="{{ $index % 2 == 0 ? 'background-color: #F8F9FA;' : '' }}">
                <td style="border: 1px solid #ccc; text-align: center;">
                    {{ $accountData['account']->kode }}
                </td>
                <td style="border: 1px solid #ccc; padding-left: 5px;">
                    {{ $accountData['account']->nama }}
                </td>
                <td style="border: 1px solid #ccc; text-align: center;">
                    {{ strtoupper($accountData['account']->kategori) }}
                </td>
                <td
                    style="border: 1px solid #ccc; text-align: right; padding-right: 5px; {{ $accountData['opening_balance'] < 0 ? 'color: red;' : '' }}">
                    {{ $accountData['opening_balance'] < 0 ? '(' : '' }}{{ number_format(abs($accountData['opening_balance']), 0, ',', '.') }}{{ $accountData['opening_balance'] < 0 ? ')' : '' }}
                </td>
                <td style="border: 1px solid #ccc; text-align: right; padding-right: 5px;">
                    {{ number_format($accountData['period_debit'], 0, ',', '.') }}
                </td>
                <td style="border: 1px solid #ccc; text-align: right; padding-right: 5px;">
                    {{ number_format($accountData['period_kredit'], 0, ',', '.') }}
                </td>
                <td
                    style="border: 1px solid #ccc; text-align: right; padding-right: 5px; {{ $accountData['period_mutation'] < 0 ? 'color: red;' : 'color: green;' }}">
                    {{ $accountData['period_mutation'] < 0 ? '(' : '' }}{{ number_format(abs($accountData['period_mutation']), 0, ',', '.') }}{{ $accountData['period_mutation'] < 0 ? ')' : '' }}
                </td>
                <td
                    style="border: 1px solid #ccc; text-align: right; padding-right: 5px; font-weight: bold; {{ $accountData['ending_balance'] < 0 ? 'color: red;' : 'color: green;' }}">
                    {{ $accountData['ending_balance'] < 0 ? '(' : '' }}{{ number_format(abs($accountData['ending_balance']), 0, ',', '.') }}{{ $accountData['ending_balance'] < 0 ? ')' : '' }}
                </td>
                <td style="border: 1px solid #ccc; text-align: center; font-size: 8px;">
                    @if ($accountData['ending_balance'] > 0)
                        <span style="color: green;">✓</span>
                    @elseif($accountData['ending_balance'] < 0)
                        <span style="color: red;">⚠</span>
                    @else
                        <span style="color: gray;">○</span>
                    @endif
                </td>
            </tr>
        @endforeach

        <!-- Empty Rows -->
        <tr>
            <td colspan="9"></td>
        </tr>
        <tr>
            <td colspan="9"></td>
        </tr>

        <!-- Summary Totals -->
        <tr style="background-color: #E3F2FD; font-weight: bold;">
            <td colspan="3" style="border: 1px solid #000; text-align: center; font-weight: bold;">GRAND TOTAL</td>
            <td style="border: 1px solid #000; text-align: right; padding-right: 5px; font-weight: bold;">
                {{ number_format(collect($accounts)->sum('opening_balance'), 0, ',', '.') }}
            </td>
            <td style="border: 1px solid #000; text-align: right; padding-right: 5px; font-weight: bold;">
                {{ number_format(collect($accounts)->sum('period_debit'), 0, ',', '.') }}
            </td>
            <td style="border: 1px solid #000; text-align: right; padding-right: 5px; font-weight: bold;">
                {{ number_format(collect($accounts)->sum('period_kredit'), 0, ',', '.') }}
            </td>
            <td style="border: 1px solid #000; text-align: right; padding-right: 5px; font-weight: bold;">
                {{ number_format(collect($accounts)->sum('period_mutation'), 0, ',', '.') }}
            </td>
            <td style="border: 1px solid #000; text-align: right; padding-right: 5px; font-weight: bold;">
                {{ number_format($grandTotal, 0, ',', '.') }}
            </td>
            <td style="border: 1px solid #000;"></td>
        </tr>

        <!-- Empty Rows -->
        <tr>
            <td colspan="9"></td>
        </tr>
        <tr>
            <td colspan="9"></td>
        </tr>

        <!-- Footer Info -->
        <tr>
            <td colspan="9" style="text-align: left; font-size: 10px;">
                Dicetak pada: {{ date('d/m/Y H:i:s') }}
            </td>
        </tr>
        <tr>
            <td colspan="9" style="text-align: left; font-size: 10px;">
                Total Akun: {{ count($accounts) }} akun dengan saldo
            </td>
        </tr>
        <tr>
            <td colspan="9" style="text-align: left; font-size: 10px;">
                Dibuat oleh: {{ auth()->user()->name ?? 'System' }}
            </td>
        </tr>
        <tr>
            <td colspan="9" style="text-align: left; font-size: 10px;">
                Keterangan: ✓ = Saldo Positif, ⚠ = Saldo Negatif, ○ = Saldo Nol
            </td>
        </tr>
    </table>
</body>

</html>
