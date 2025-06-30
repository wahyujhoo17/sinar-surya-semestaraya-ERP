<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Jurnal Umum Export</title>
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
                JURNAL UMUM
            </td>
        </tr>

        <!-- Filter Information -->
        @if ($startDate && $endDate)
            <tr>
                <td colspan="9" style="text-align: center; font-size: 10px;">
                    Periode: {{ date('d/m/Y', strtotime($startDate)) }} - {{ date('d/m/Y', strtotime($endDate)) }}
                </td>
            </tr>
        @endif

        @if ($selectedAkun)
            <tr>
                <td colspan="9" style="text-align: center; font-size: 10px;">
                    Filter Akun: {{ $selectedAkun->kode }} - {{ $selectedAkun->nama }}
                </td>
            </tr>
        @endif

        <!-- Empty Row -->
        <tr>
            <td colspan="9"></td>
        </tr>

        <!-- Table Headers -->
        <tr style="background-color: #4472C4; color: white; font-weight: bold;">
            <td style="border: 1px solid #000; text-align: center;">Tanggal</td>
            <td style="border: 1px solid #000; text-align: center;">No. Referensi</td>
            <td style="border: 1px solid #000; text-align: center;">Kode Akun</td>
            <td style="border: 1px solid #000; text-align: center;">Nama Akun</td>
            <td style="border: 1px solid #000; text-align: center;">Keterangan</td>
            <td style="border: 1px solid #000; text-align: center;">Debit (Rp)</td>
            <td style="border: 1px solid #000; text-align: center;">Kredit (Rp)</td>
            <td style="border: 1px solid #000; text-align: center;">Dibuat Oleh</td>
            <td style="border: 1px solid #000; text-align: center;">Tanggal Dibuat</td>
        </tr>

        <!-- Data Rows -->
        @foreach ($jurnals as $index => $jurnal)
            <tr style="{{ $index % 2 == 0 ? 'background-color: #F8F9FA;' : '' }}">
                <td style="border: 1px solid #ccc; text-align: center;">
                    {{ date('d/m/Y', strtotime($jurnal->tanggal)) }}
                </td>
                <td style="border: 1px solid #ccc; text-align: center;">
                    {{ $jurnal->no_referensi ?? '-' }}
                </td>
                <td style="border: 1px solid #ccc; text-align: center;">
                    {{ $jurnal->akun->kode ?? '-' }}
                </td>
                <td style="border: 1px solid #ccc; padding-left: 5px;">
                    {{ $jurnal->akun->nama ?? '-' }}
                </td>
                <td style="border: 1px solid #ccc; padding-left: 5px;">
                    {{ $jurnal->keterangan ?? '-' }}
                </td>
                <td style="border: 1px solid #ccc; text-align: right; padding-right: 5px;">
                    @if ($jurnal->debit > 0)
                        {{ number_format($jurnal->debit, 0, ',', '.') }}
                    @else
                        0
                    @endif
                </td>
                <td style="border: 1px solid #ccc; text-align: right; padding-right: 5px;">
                    @if ($jurnal->kredit > 0)
                        {{ number_format($jurnal->kredit, 0, ',', '.') }}
                    @else
                        0
                    @endif
                </td>
                <td style="border: 1px solid #ccc; text-align: center;">
                    {{ $jurnal->user->name ?? '-' }}
                </td>
                <td style="border: 1px solid #ccc; text-align: center;">
                    {{ $jurnal->created_at->format('d/m/Y H:i') }}
                </td>
            </tr>
        @endforeach

        <!-- Empty Row -->
        <tr>
            <td colspan="9"></td>
        </tr>

        <!-- Totals Row -->
        <tr style="background-color: #E3F2FD; font-weight: bold;">
            <td colspan="5" style="border: 1px solid #000; text-align: center; font-weight: bold;">TOTAL</td>
            <td style="border: 1px solid #000; text-align: right; padding-right: 5px; font-weight: bold;">
                {{ number_format($totalDebit, 0, ',', '.') }}
            </td>
            <td style="border: 1px solid #000; text-align: right; padding-right: 5px; font-weight: bold;">
                {{ number_format($totalKredit, 0, ',', '.') }}
            </td>
            <td colspan="2" style="border: 1px solid #000;"></td>
        </tr>

        <!-- Balance Check Row -->
        <tr style="background-color: {{ $totalDebit == $totalKredit ? '#D4F3E7' : '#F8D7DA' }}; font-weight: bold;">
            <td colspan="5" style="border: 1px solid #000; text-align: center; font-weight: bold;">
                SELISIH (DEBIT - KREDIT)
            </td>
            <td colspan="2"
                style="border: 1px solid #000; text-align: center; font-weight: bold; {{ $totalDebit == $totalKredit ? 'color: green;' : 'color: red;' }}">
                {{ number_format($totalDebit - $totalKredit, 0, ',', '.') }}
                @if ($totalDebit == $totalKredit)
                    (BALANCE)
                @else
                    (NOT BALANCE)
                @endif
            </td>
            <td colspan="2" style="border: 1px solid #000;"></td>
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
                Total Transaksi: {{ count($jurnals) }} records
            </td>
        </tr>
        <tr>
            <td colspan="9" style="text-align: left; font-size: 10px;">
                Dibuat oleh: {{ auth()->user()->name ?? 'System' }}
            </td>
        </tr>
    </table>
</body>

</html>
