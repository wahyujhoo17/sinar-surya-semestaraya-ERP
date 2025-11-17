<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Piutang Usaha</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 20mm 15mm 25mm 15mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            color: #2d3748;
            line-height: 1.4;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        /* Header dengan Logo */
        .header {
            background: #f8fafc;
            padding: 12px 15px;
            margin-bottom: 15px;
            border-bottom: 2px solid #3b82f6;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: middle;
            padding: 5px;
        }

        .header-logo {
            width: 20%;
            text-align: left;
        }

        .logo-img {
            max-width: 90px;
            max-height: 40px;
            height: auto;
            background: white;
            padding: 5px 8px;
            border: 1px solid #e2e8f0;
        }

        .company-name {
            font-size: 11px;
            font-weight: bold;
            color: #1e40af;
            letter-spacing: 0.5px;
        }

        .header-title-section {
            width: 60%;
            text-align: center;
        }

        .header-title {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 3px;
            color: #1e293b;
        }

        .header-subtitle {
            font-size: 8px;
            font-weight: normal;
            color: #64748b;
            margin-top: 3px;
        }

        .header-date {
            width: 20%;
            text-align: right;
            font-size: 7.5px;
            color: #64748b;
        }

        .print-date {
            background: white;
            padding: 5px 8px;
            border: 1px solid #e2e8f0;
        }

        /* Info Card */
        .info-card {
            background: #ffffff;
            border-left: 3px solid #3b82f6;
            padding: 8px 12px;
            margin-bottom: 12px;
            border: 1px solid #e2e8f0;
            font-size: 8px;
        }

        .sorting-info {
            margin: 5px 0 8px 0;
            font-style: italic;
            font-size: 7.5px;
            color: #64748b;
        }

        /* Table */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 7.5px;
        }

        .data-table th {
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
            padding: 5px 3px;
            text-align: left;
            font-weight: bold;
            font-size: 7px;
            color: #1e293b;
        }

        .data-table td {
            border: 1px solid #e2e8f0;
            padding: 4px 3px;
            text-align: left;
            word-wrap: break-word;
        }

        .data-table tbody tr:nth-child(odd) {
            background: #f9fafb;
        }

        .data-table tbody tr:nth-child(even) {
            background: white;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Footer Table */
        .data-table tfoot th {
            font-size: 8px;
            font-weight: bold;
            background: #dbeafe;
            color: #1e40af;
            padding: 6px 3px;
        }

        /* Summary */
        .summary {
            margin-top: 12px;
            padding: 10px 12px;
            border: 2px solid #cbd5e0;
            background-color: #f1f5f9;
        }

        .summary p {
            margin: 3px 0;
            font-size: 9px;
            color: #334155;
        }

        .summary strong {
            font-size: 10px;
        }

        /* Payment Details */
        .payment-details {
            font-size: 7px;
            color: #059669;
            margin-top: 2px;
        }

        /* Footer Section */
        .footer-section {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 7px;
            color: #64748b;
        }
    </style>
</head>

<body>
    <div class="container">
        {{-- Header dengan Logo --}}
        <div class="header">
            <table class="header-table">
                <tr>
                    <td class="header-logo">
                        @php
                            $logoPath = public_path('img/SemestaPro.PNG');
                            $logoBase64 = '';
                            if (file_exists($logoPath)) {
                                $logoData = file_get_contents($logoPath);
                                $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
                            }
                        @endphp
                        @if ($logoBase64)
                            <img src="{{ $logoBase64 }}" alt="Logo" class="logo-img">
                        @else
                            <div class="company-name">SEMESTAPRO</div>
                        @endif
                    </td>
                    <td class="header-title-section">
                        <div class="header-title">Laporan Piutang Usaha</div>
                        <div class="header-subtitle">
                            @if (request('customer_id'))
                                @php
                                    $customer = \App\Models\Customer::find(request('customer_id'));
                                @endphp
                                Customer: {{ $customer ? $customer->nama : 'Semua Customer' }}
                            @else
                                Customer: Semua Customer
                            @endif
                            @if (request('start_date') && request('end_date'))
                                | Periode: {{ date('d/m/Y', strtotime(request('start_date'))) }} -
                                {{ date('d/m/Y', strtotime(request('end_date'))) }}
                            @elseif(request('start_date'))
                                | Mulai: {{ date('d/m/Y', strtotime(request('start_date'))) }}
                            @elseif(request('end_date'))
                                | Sampai: {{ date('d/m/Y', strtotime(request('end_date'))) }}
                            @endif
                        </div>
                    </td>
                    <td class="header-date">
                        <div class="print-date">
                            <strong>Dicetak:</strong><br>
                            {{ date('d/m/Y', strtotime($tanggalCetak)) }}<br>
                            {{ date('H:i', strtotime($tanggalCetak)) }} WIB
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="sorting-info">
            Diurutkan berdasarkan: {{ ucfirst(str_replace('_', ' ', $sortColumn)) }}
            ({{ $sortDirection === 'asc' ? 'Naik' : 'Turun' }})
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 3%;">No</th>
                    <th style="width: 9%;">Nomor Invoice</th>
                    <th style="width: 7%;">Tgl Invoice</th>
                    <th style="width: 8%;">Nomor SO</th>
                    <th style="width: 12%;">Customer</th>
                    <th style="width: 8%;" class="text-right">Total Invoice</th>
                    <th style="width: 8%;" class="text-right">Total Bayar</th>
                    <th style="width: 18%;">Riwayat Pembayaran</th>
                    <th style="width: 8%;" class="text-right">Sisa Piutang</th>
                    <th style="width: 7%;" class="text-center">Jatuh Tempo</th>
                    <th style="width: 7%;" class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($invoices as $index => $invoice)
                    @php
                        $totalPayments = $invoice->pembayaranPiutang->sum('jumlah');
                        $sisaPiutang = $invoice->sisa_piutang;
                        // Get all payments sorted by date descending
                        $allPayments = $invoice->pembayaranPiutang->sortByDesc('tanggal');

                        $isOverdue =
                            $invoice->jatuh_tempo &&
                            \Carbon\Carbon::parse($invoice->jatuh_tempo)
                                ->startOfDay()
                                ->lt(\Carbon\Carbon::today()->startOfDay());

                        if ($sisaPiutang <= 0 && $invoice->status === 'lunas') {
                            $statusDisplay = 'Lunas';
                        } elseif ($isOverdue && $invoice->status !== 'lunas') {
                            $statusDisplay = 'Jatuh Tempo';
                        } elseif ($invoice->status === 'sebagian') {
                            $statusDisplay = 'Lunas Sebagian';
                        } elseif ($invoice->status === 'belum_bayar') {
                            $statusDisplay = 'Belum Bayar';
                        } elseif ($sisaPiutang <= 0) {
                            $statusDisplay = 'Lunas';
                        } else {
                            $statusDisplay = ucfirst(str_replace('_', ' ', $invoice->status ?? 'Tidak Diketahui'));
                        }
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $invoice->nomor }}</td>
                        <td>{{ date('d/m/Y', strtotime($invoice->tanggal)) }}</td>
                        <td>{{ $invoice->salesOrder ? $invoice->salesOrder->nomor : '-' }}</td>
                        <td>{{ $invoice->customer->company ?? ($invoice->customer->nama ?? 'N/A') }}</td>
                        <td class="text-right">{{ number_format($invoice->total, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($totalPayments, 0, ',', '.') }}</td>
                        <td>
                            @if ($allPayments->count() > 0)
                                @foreach ($allPayments as $payment)
                                    <div
                                        style="margin-bottom: 3px; border-bottom: 1px dashed #e2e8f0; padding-bottom: 2px;">
                                        <strong style="font-size: 7px;">{{ $payment->nomor ?? '-' }}</strong><br>
                                        <span
                                            style="font-size: 6.5px; color: #64748b;">{{ date('d/m/Y', strtotime($payment->tanggal)) }}</span>
                                        <div class="payment-details">Rp
                                            {{ number_format($payment->jumlah, 0, ',', '.') }}</div>
                                    </div>
                                @endforeach
                            @else
                                <span style="color: #94a3b8; font-size: 7px;">Belum ada pembayaran</span>
                            @endif
                        </td>
                        <td class="text-right">{{ number_format($sisaPiutang, 0, ',', '.') }}</td>
                        <td class="text-center">
                            {{ $invoice->jatuh_tempo ? date('d/m/Y', strtotime($invoice->jatuh_tempo)) : '-' }}
                        </td>
                        <td class="text-center" style="font-size: 7px;">{{ $statusDisplay }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center" style="padding: 20px; color: #94a3b8;">Tidak ada data
                            piutang usaha.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="8" class="text-right">Total Sisa Piutang:</th>
                    <th class="text-right">{{ number_format($totalPiutangPdf, 0, ',', '.') }}</th>
                    <th colspan="2"></th>
                </tr>
            </tfoot>
        </table>

        <div class="summary">
            <p><strong>RINGKASAN PIUTANG</strong></p>
            <p>Total Invoice: <strong>{{ $invoices->count() }}</strong></p>
            <p>Total Keseluruhan Sisa Piutang: <strong
                    style="color: {{ $totalPiutangPdf > 0 ? '#dc2626' : '#059669' }};">Rp
                    {{ number_format($totalPiutangPdf, 0, ',', '.') }}</strong></p>
        </div>

        <div class="footer-section">
            <p>
                Dokumen ini dicetak otomatis oleh sistem <strong>{{ config('app.name', 'SemestaPro ERP') }}</strong>
                pada {{ now()->format('d/m/Y H:i:s') }}
                <br>
                Dokumen ini sah tanpa tanda tangan dan stempel
            </p>
        </div>

    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->getFont("Arial", "bold");
            $size = 8.5;
            
            $pageNum = "{PAGE_NUM}";
            $pageCount = "{PAGE_COUNT}";
            $pageText = "Halaman " . $pageNum . " dari " . $pageCount;
            
            $y = $pdf->get_height() - 30;
            $x = $pdf->get_width() - 150;
            
            $pdf->page_text($x, $y, $pageText, $font, $size, array(0.4, 0.4, 0.4));
        }
    </script>
</body>

</html>
