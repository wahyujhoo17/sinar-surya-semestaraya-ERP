<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pembayaran Piutang - {{ $pembayaran->nomor }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #2d3748;
            padding-bottom: 12px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0 0 5px 0;
            color: #1a202c;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            font-size: 11px;
            color: #718096;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .info-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .info-table {
            width: 100%;
        }
        .info-table td {
            padding: 3px 0;
            font-size: 11px;
        }
        .info-table td.label {
            width: 35%;
            font-weight: bold;
            color: #4a5568;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th {
            background-color: #edf2f7;
            border: 1px solid #cbd5e0;
            padding: 8px;
            font-size: 11px;
            font-weight: bold;
            color: #2d3748;
        }
        .data-table td {
            border: 1px solid #cbd5e0;
            padding: 7px 8px;
            font-size: 11px;
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
        .summary-box {
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px 16px;
            margin-bottom: 25px;
        }
        .signature-section {
            width: 100%;
            margin-top: 40px;
        }
        .signature-table {
            width: 100%;
            text-align: center;
        }
        .signature-table td {
            width: 33.33%;
            padding: 10px;
            vertical-align: top;
        }
        .signature-space {
            height: 65px;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 15px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 16px; background-color: #10b981; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
            Cetak Dokumen
        </button>
    </div>

    <div class="header">
        <h1>BUKTI PENERIMAAN PEMBAYARAN PIUTANG</h1>
        <p>PT SINAR SURYA SEMESTARAYA</p>
    </div>

    <div class="info-grid">
        <div class="info-col">
            <table class="info-table">
                <tr>
                    <td class="label">Nomor Pembayaran</td>
                    <td>: <strong>{{ $pembayaran->nomor }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Tanggal</td>
                    <td>: {{ date('d/m/Y', strtotime($pembayaran->tanggal)) }}</td>
                </tr>
                <tr>
                    <td class="label">Customer</td>
                    <td>: {{ $pembayaran->customer->nama ?? ($pembayaran->invoice->customer->nama ?? '-') }}</td>
                </tr>
                @if (isset($pembayaran->customer->company) && $pembayaran->customer->company)
                <tr>
                    <td class="label">Perusahaan</td>
                    <td>: {{ $pembayaran->customer->company }}</td>
                </tr>
                @endif
            </table>
        </div>
        <div class="info-col">
            <table class="info-table">
                <tr>
                    <td class="label">Metode Pembayaran</td>
                    <td>: {{ $pembayaran->metode_pembayaran }}</td>
                </tr>
                <tr>
                    <td class="label">Rekening / Kas</td>
                    <td>:
                        @if ($pembayaran->metode_pembayaran === 'Bank Transfer' && $pembayaran->rekeningBank)
                            {{ $pembayaran->rekeningBank->nama_bank }} - {{ $pembayaran->rekeningBank->nomor_rekening }}
                        @elseif($pembayaran->metode_pembayaran === 'Kas' && $pembayaran->kas)
                            {{ $pembayaran->kas->nama }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">No. Referensi</td>
                    <td>: {{ $pembayaran->no_referensi ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="data-table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 35px;">No.</th>
                    <th>Nomor Invoice</th>
                    <th class="text-center">Tanggal Inv.</th>
                    <th class="text-right">Total Invoice</th>
                    <th class="text-right">Alokasi Pembayaran</th>
                    <th class="text-right">Sisa Piutang</th>
                </tr>
            </thead>
            <tbody>
                @if ($pembayaran->details->count() > 0)
                    @foreach ($pembayaran->details as $index => $detail)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="font-bold">{{ $detail->invoice->nomor ?? '-' }}</td>
                            <td class="text-center">{{ $detail->invoice && $detail->invoice->tanggal ? date('d/m/Y', strtotime($detail->invoice->tanggal)) : '-' }}</td>
                            <td class="text-right">Rp {{ number_format($detail->invoice->total ?? 0, 0, ',', '.') }}</td>
                            <td class="text-right font-bold">Rp {{ number_format($detail->jumlah, 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($detail->invoice->sisa_piutang ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @elseif($pembayaran->invoice)
                    <tr>
                        <td class="text-center">1</td>
                        <td class="font-bold">{{ $pembayaran->invoice->nomor }}</td>
                        <td class="text-center">{{ date('d/m/Y', strtotime($pembayaran->invoice->tanggal)) }}</td>
                        <td class="text-right">Rp {{ number_format($pembayaran->invoice->total, 0, ',', '.') }}</td>
                        <td class="text-right font-bold">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($pembayaran->invoice->sisa_piutang, 0, ',', '.') }}</td>
                    </tr>
                @else
                    <tr>
                        <td colspan="6" class="text-center">Pembayaran Umum (Tidak tertaut langsung ke invoice spesifik)</td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr style="background-color: #f7fafc;">
                    <td colspan="4" class="text-right font-bold">Total Diterima:</td>
                    <td class="text-right font-bold" style="color: #059669; font-size: 13px;">
                        Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    @if ($pembayaran->catatan)
    <div class="summary-box">
        <strong>Catatan:</strong> {{ $pembayaran->catatan }}
    </div>
    @endif

    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td>
                    <p>Diterima Oleh (Kasir / Finance),</p>
                    <div class="signature-space"></div>
                    <p class="font-bold">({{ $pembayaran->user->name ?? '.......................' }})</p>
                </td>
                <td>
                    <p>Customer,</p>
                    <div class="signature-space"></div>
                    <p class="font-bold">({{ $pembayaran->customer->nama ?? ($pembayaran->invoice->customer->nama ?? '.......................') }})</p>
                </td>
                <td>
                    <p>Mengetahui (Pimpinan),</p>
                    <div class="signature-space"></div>
                    <p class="font-bold">(.......................)</p>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
