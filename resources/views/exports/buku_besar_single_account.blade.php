<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Buku Besar - {{ $bukuBesarData['akun']->kode }}</title>
</head>

<body>
    <table>
        <!-- Header Company -->
        <tr>
            <td colspan="6" style="text-align: center; font-weight: bold; font-size: 16px;">
                PT SINAR SURYA SEMESTARAYA
            </td>
        </tr>
        <tr>
            <td colspan="6" style="text-align: center; font-weight: bold; font-size: 14px;">
                BUKU BESAR
            </td>
        </tr>
        <tr>
            <td colspan="6" style="text-align: center; font-size: 10px;">
                Periode: {{ date('d/m/Y', strtotime($tanggalAwal)) }} - {{ date('d/m/Y', strtotime($tanggalAkhir)) }}
            </td>
        </tr>

        <!-- Empty Row -->
        <tr>
            <td colspan="6"></td>
        </tr>

        <!-- Account Info -->
        <tr style="background-color: #E3F2FD; font-weight: bold;">
            <td colspan="6" style="text-align: center; font-weight: bold; font-size: 12px;">
                INFORMASI AKUN
            </td>
        </tr>

        <tr style="background-color: #F8F9FA;">
            <td style="border: 1px solid #ccc; font-weight: bold;">Kode Akun:</td>
            <td style="border: 1px solid #ccc;">{{ $bukuBesarData['akun']->kode }}</td>
            <td style="border: 1px solid #ccc; font-weight: bold;">Kategori:</td>
            <td style="border: 1px solid #ccc;">{{ strtoupper($bukuBesarData['akun']->kategori) }}</td>
            <td style="border: 1px solid #ccc; font-weight: bold;">Saldo Akhir:</td>
            <td
                style="border: 1px solid #ccc; text-align: right; font-weight: bold; {{ $bukuBesarData['ending_balance'] < 0 ? 'color: red;' : 'color: green;' }}">
                {{ $bukuBesarData['ending_balance'] < 0 ? '(' : '' }}{{ number_format(abs($bukuBesarData['ending_balance']), 0, ',', '.') }}{{ $bukuBesarData['ending_balance'] < 0 ? ')' : '' }}
            </td>
        </tr>

        <tr style="background-color: #F8F9FA;">
            <td style="border: 1px solid #ccc; font-weight: bold;">Nama Akun:</td>
            <td colspan="3" style="border: 1px solid #ccc;">{{ $bukuBesarData['akun']->nama }}</td>
            <td style="border: 1px solid #ccc; font-weight: bold;">Total Transaksi:</td>
            <td style="border: 1px solid #ccc; text-align: right; font-weight: bold;">
                {{ $bukuBesarData['total_transaksi'] }}</td>
        </tr>

        <!-- Empty Rows -->
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td colspan="6"></td>
        </tr>

        <!-- Table Headers -->
        <tr style="background-color: #4472C4; color: white; font-weight: bold;">
            <td style="border: 1px solid #000; text-align: center;">Tanggal</td>
            <td style="border: 1px solid #000; text-align: center;">No. Referensi</td>
            <td style="border: 1px solid #000; text-align: center;">Keterangan</td>
            <td style="border: 1px solid #000; text-align: center;">Debit (Rp)</td>
            <td style="border: 1px solid #000; text-align: center;">Kredit (Rp)</td>
            <td style="border: 1px solid #000; text-align: center;">Saldo (Rp)</td>
        </tr>

        <!-- Opening Balance Row -->
        <tr style="background-color: #F8F9FA; font-weight: bold;">
            <td style="border: 1px solid #ccc; text-align: center;">
                {{ date('d/m/Y', strtotime($tanggalAwal . ' -1 day')) }}
            </td>
            <td style="border: 1px solid #ccc; text-align: center;">-</td>
            <td style="border: 1px solid #ccc; font-weight: bold;">SALDO AWAL PERIODE</td>
            <td style="border: 1px solid #ccc; text-align: center;">-</td>
            <td style="border: 1px solid #ccc; text-align: center;">-</td>
            <td
                style="border: 1px solid #ccc; text-align: right; font-weight: bold; {{ $bukuBesarData['opening_balance'] < 0 ? 'color: red;' : 'color: green;' }}">
                {{ $bukuBesarData['opening_balance'] < 0 ? '(' : '' }}{{ number_format(abs($bukuBesarData['opening_balance']), 0, ',', '.') }}{{ $bukuBesarData['opening_balance'] < 0 ? ')' : '' }}
            </td>
        </tr>

        <!-- Transaction Rows -->
        @foreach ($bukuBesarData['transaksi'] as $index => $item)
            <tr style="{{ $index % 2 == 0 ? 'background-color: #F8F9FA;' : '' }}">
                <td style="border: 1px solid #ccc; text-align: center;">
                    {{ date('d/m/Y', strtotime($item['transaksi']->tanggal)) }}
                </td>
                <td style="border: 1px solid #ccc; text-align: center;">
                    {{ $item['transaksi']->no_referensi ?? '-' }}
                </td>
                <td style="border: 1px solid #ccc; padding-left: 5px;">
                    {{ $item['transaksi']->keterangan ?? '-' }}
                </td>
                <td style="border: 1px solid #ccc; text-align: right; padding-right: 5px;">
                    @if ($item['transaksi']->debit > 0)
                        {{ number_format($item['transaksi']->debit, 0, ',', '.') }}
                    @else
                        0
                    @endif
                </td>
                <td style="border: 1px solid #ccc; text-align: right; padding-right: 5px;">
                    @if ($item['transaksi']->kredit > 0)
                        {{ number_format($item['transaksi']->kredit, 0, ',', '.') }}
                    @else
                        0
                    @endif
                </td>
                <td
                    style="border: 1px solid #ccc; text-align: right; padding-right: 5px; font-weight: bold; {{ $item['saldo'] < 0 ? 'color: red;' : 'color: green;' }}">
                    {{ $item['saldo'] < 0 ? '(' : '' }}{{ number_format(abs($item['saldo']), 0, ',', '.') }}{{ $item['saldo'] < 0 ? ')' : '' }}
                </td>
            </tr>
        @endforeach

        <!-- Summary Row -->
        <tr style="background-color: #E3F2FD; font-weight: bold;">
            <td colspan="3" style="border: 1px solid #000; text-align: center; font-weight: bold;">TOTAL PERIODE</td>
            <td style="border: 1px solid #000; text-align: right; padding-right: 5px; font-weight: bold;">
                {{ number_format($bukuBesarData['period_debit'], 0, ',', '.') }}
            </td>
            <td style="border: 1px solid #000; text-align: right; padding-right: 5px; font-weight: bold;">
                {{ number_format($bukuBesarData['period_kredit'], 0, ',', '.') }}
            </td>
            <td
                style="border: 1px solid #000; text-align: right; padding-right: 5px; font-weight: bold; {{ $bukuBesarData['ending_balance'] < 0 ? 'color: red;' : 'color: green;' }}">
                {{ $bukuBesarData['ending_balance'] < 0 ? '(' : '' }}{{ number_format(abs($bukuBesarData['ending_balance']), 0, ',', '.') }}{{ $bukuBesarData['ending_balance'] < 0 ? ')' : '' }}
            </td>
        </tr>

        <!-- Empty Rows -->
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td colspan="6"></td>
        </tr>

        <!-- Footer Info -->
        <tr>
            <td colspan="6" style="text-align: left; font-size: 10px;">
                Dicetak pada: {{ date('d/m/Y H:i:s') }}
            </td>
        </tr>
        <tr>
            <td colspan="6" style="text-align: left; font-size: 10px;">
                Total Transaksi: {{ count($bukuBesarData['transaksi']) }} records
            </td>
        </tr>
        <tr>
            <td colspan="6" style="text-align: left; font-size: 10px;">
                Dibuat oleh: {{ auth()->user()->name ?? 'System' }}
            </td>
        </tr>
    </table>
</body>

</html>
