<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pengembalian Dana #{{ $refund->nomor }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .company-name {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .company-address {
            font-size: 10pt;
            margin-bottom: 5px;
        }

        .document-title {
            font-size: 14pt;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
            text-decoration: underline;
        }

        table.info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.info th {
            text-align: left;
            width: 150px;
            padding: 5px;
            vertical-align: top;
        }

        table.info td {
            padding: 5px;
            vertical-align: top;
        }

        .amount {
            border: 1px solid #000;
            padding: 10px;
            margin: 20px 0;
        }

        .amount-title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .amount-value {
            font-size: 14pt;
            font-weight: bold;
            text-align: center;
        }

        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            width: 45%;
            text-align: center;
        }

        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        .footer {
            margin-top: 50px;
            font-size: 10pt;
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 10px;
        }

        .notes {
            border: 1px solid #000;
            padding: 10px;
            margin: 20px 0;
            min-height: 50px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="company-name">PT. SINAR SURYA</div>
            <div class="company-address">
                Jl. Raya Industri No. 123, Kawasan Industri<br>
                Telp: (021) 123-4567 | Email: info@sinar-surya.co.id
            </div>
        </div>

        <div class="document-title">BUKTI PENGEMBALIAN DANA</div>

        <table class="info">
            <tr>
                <th>Nomor</th>
                <td>: {{ $refund->nomor }}</td>
                <th>Tanggal</th>
                <td>: {{ \Carbon\Carbon::parse($refund->tanggal)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Supplier</th>
                <td>: {{ $refund->supplier->nama }}</td>
                <th>No. Purchase Order</th>
                <td>: {{ $refund->purchaseOrder->nomor }}</td>
            </tr>
            <tr>
                <th>Metode Penerimaan</th>
                <td>: {{ ucfirst($refund->metode_penerimaan) }}</td>
                <th>No. Referensi</th>
                <td>: {{ $refund->no_referensi ?? '-' }}</td>
            </tr>
            <tr>
                <th>Akun</th>
                <td colspan="3">:
                    @if ($refund->metode_penerimaan == 'kas')
                        {{ $refund->kas->nama ?? '-' }}
                    @elseif($refund->metode_penerimaan == 'bank')
                        {{ $refund->rekeningBank->nama_bank ?? '-' }} - {{ $refund->rekeningBank->nomor ?? '-' }}
                        ({{ $refund->rekeningBank->atas_nama ?? '-' }})
                    @else
                        -
                    @endif
                </td>
            </tr>
        </table>

        <div class="amount">
            <div class="amount-title">Jumlah Pengembalian:</div>
            <div class="amount-value">Rp {{ number_format($refund->jumlah, 0, ',', '.') }}</div>
            <div style="font-style: italic; margin-top: 10px; text-align: center">
                {{ App\Helpers\CurrencyHelper::terbilang($refund->jumlah) }} rupiah
            </div>
        </div>

        <div class="notes">
            <div style="font-weight: bold; margin-bottom: 5px;">Catatan:</div>
            {{ $refund->catatan ?? '' }}
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <div>Diterima oleh,</div>
                <div class="signature-line">
                    ( {{ $refund->supplier->nama }} )
                </div>
            </div>
            <div class="signature-box">
                <div>Dibuat oleh,</div>
                <div class="signature-line">
                    ( {{ $refund->user->name ?? '' }} )
                </div>
            </div>
        </div>

        <div class="footer">
            Dokumen ini dicetak pada tanggal {{ now()->format('d/m/Y H:i:s') }} dan merupakan dokumen resmi perusahaan.
        </div>
    </div>
</body>

</html>
