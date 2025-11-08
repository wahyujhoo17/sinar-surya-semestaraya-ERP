<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invoice - {{ $invoice->nomor }}</title>
    <style>
        body {
            background: #ffffff;
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 11px;
            line-height: 1.3;
        }

        .sheet {
            max-width: 100%;
            margin: 0;
            background: #ffffff;
            padding: 0;
            min-height: 100vh;
        }

        @page {
            size: A4;
            margin: 8mm 5mm;
        }

        .main-content {
            min-height: calc(100vh - 60px);
            width: 100%;
            max-width: 100%;
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        .header-section {
            width: calc(100% - 60px);
            background-color: #1F2A44;
            color: white;
            padding: 15px 20px;
            margin: 0 10px 15px 10px;
            position: relative;
            box-sizing: border-box;
        }

        .header-content {
            width: 100%;
        }

        .company-info {
            float: left;
            width: 65%;
            margin-top: 4%;
        }

        .invoice-info {
            float: right;
            width: 30%;
            text-align: right;
            padding-right: 8px;
        }

        .company-logo {
            float: left;
            margin-right: 15px;
            max-height: 50px;
            max-width: 50px;
            background-color: white;
            border-radius: 50%;
            padding: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .company-name {
            font-weight: bold;
            font-size: 16px;
            margin: 5px 0 2px 0;
            color: white;
        }

        .company-tagline {
            font-size: 11px;
            margin: 0;
            opacity: 0.9;
            color: #94a3b8;
        }

        .invoice-title {
            color: #E74C3C;
            font-weight: bold;
            font-size: 22px;
            margin: 0 0 8px 0;
            letter-spacing: 1.5px;
        }

        .invoice-meta {
            font-size: 11px;
            color: #94a3b8;
        }

        .invoice-meta div {
            margin-bottom: 2px;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        .invoice-details {
            width: calc(100% - 60px);
            margin: 0 10px 15px 10px;
            padding: 15px 20px;
        }

        .invoice-to {
            float: left;
            width: 60%;
        }

        .payment-method {
            float: right;
            width: 35%;
        }

        .section-title {
            color: #E74C3C;
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 12px;
            border-bottom: 1px solid #E74C3C;
            padding-bottom: 2px;
        }

        .customer-name {
            font-weight: bold;
            color: #0f172a;
            margin: 5px 0;
            font-size: 13px;
        }

        .customer-details {
            color: #475569;
            line-height: 1.4;
            font-size: 11px;
        }

        .customer-details p {
            margin: 3px 0;
        }

        .invoice-table {
            width: calc(100% - 20px);
            border-collapse: collapse;
            margin: 10px 10px;
            font-size: 10px;
        }

        .table-header {
            background-color: #1F2A44;
            color: white;
            font-weight: bold;
        }

        .table-header th {
            padding: 10px 8px;
            text-align: left;
            border: none;
        }

        .table-header .no-col {
            width: 6%;
            text-align: center;
        }

        .table-header .desc-col {
            width: 30%;
        }

        .table-header .qty-col {
            width: 8%;
            text-align: center;
        }

        .table-header .price-col {
            width: 14%;
            text-align: center;
        }

        .table-header .total-col {
            width: 16%;
            text-align: right;
        }

        .red-header {
            background-color: #E74C3C !important;
            position: relative;
        }

        .table-row td {
            padding: 8px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .table-row:nth-child(even) {
            background-color: #f8fafc;
        }

        .product-name {
            font-weight: bold;
            margin-bottom: 2px;
            font-size: 10px;
        }

        .product-desc {
            font-size: 9px;
            color: #475569;
            margin: 0;
            line-height: 1.3;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .total-summary {
            width: calc(100% - 60px);
            margin: 20px 30px;
            padding: 0;
            page-break-inside: avoid;
        }

        .summary-section {
            float: right;
            width: 40%;
            padding: 0;
        }

        .summary-item {
            width: 100%;
            margin-bottom: 6px;
            font-size: 11px;
            clear: both;
            box-sizing: border-box;
            padding: 2px 0;
            display: table;
            table-layout: fixed;
        }

        .summary-item .label {
            display: table-cell;
            width: 65%;
            line-height: 1.4;
            color: #475569 !important;
            font-weight: normal;
            vertical-align: top;
        }

        .summary-item .amount {
            display: table-cell;
            font-weight: bold;
            text-align: right;
            width: 35%;
            line-height: 1.4;
            color: #0f172a !important;
            vertical-align: top;
        }

        .summary-highlight {
            background-color: rgba(239, 68, 68, 0.1);
            padding: 4px 6px;
            margin: 2px -6px;
            border-radius: 3px;
        }

        .summary-highlight .label {
            color: #dc2626 !important;
            font-weight: 500;
        }

        .summary-highlight .amount {
            color: #dc2626 !important;
            font-weight: bold;
        }

        .total-final {
            width: 100%;
            margin-top: 15px;
            padding: 12px 0;
            border-top: 2px solid #E74C3C;
            font-size: 14px;
            font-weight: bold;
            color: #1F2A44;
            clear: both;
            display: table;
            table-layout: fixed;
        }

        .total-final .label {
            display: table-cell;
            width: 65%;
            vertical-align: top;
        }

        .total-final .amount {
            display: table-cell;
            text-align: right;
            width: 55%;
            color: #E74C3C;
            vertical-align: top;
        }

        .terms-signature {
            width: calc(100% - 60px);
            margin: 25px 30px;
            padding: 0;
            font-size: 10px;
            page-break-inside: avoid;
        }

        .signature-section {
            width: 100%;
            margin-top: 20px;
            clear: both;
        }

        .signature-row {
            width: 100%;
            display: table;
            table-layout: fixed;
            margin-top: 15px;
        }

        .signature-item {
            display: table-cell;
            width: 45%;
            text-align: center;
            vertical-align: top;
            padding: 0 10px;
        }

        .signature-line {
            height: 40px;
            border-bottom: 1px solid #cbd5e1;
            margin-bottom: 8px;
        }

        .signature-label {
            color: #334155;
            font-size: 10px;
            font-weight: bold;
        }

        .footer-thank-you {
            position: fixed;
            bottom: 30px;
            left: 0;
            right: 0;
            font-size: 13px;
            color: #334155;
            text-align: center;
            padding: 15px 20px;
            font-weight: bold;
            background-color: #f8fafc;
            border-top: 2px solid #E74C3C;
        }

        .footer-decoration {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 25px;
            background-color: #1F2A44;
        }

        .watermark-bg {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 100%;
            text-align: center;
            z-index: 0;
            opacity: 0.05;
            font-size: 50px;
            font-weight: bold;
            color: #1F2A44;
            transform: translate(-50%, -50%) rotate(-25deg);
            pointer-events: none;
            user-select: none;
            white-space: nowrap;
        }

        .status-belum-bayar {
            color: #dc2626;
            font-weight: bold;
        }

        .status-sebagian {
            color: #d97706;
            font-weight: bold;
        }

        .status-lunas {
            color: #16a34a;
            font-weight: bold;
        }

        .status-default {
            color: #6b7280;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="sheet">
        <div class="watermark-bg">{{ strtoupper($template_config['company_name'] ?? 'PT INDO ATSAKA INDUSTRI') }}</div>
        <div class="main-content">
            <!-- Header -->
            <div class="header-section">
                <div class="header-content clearfix">
                    <div class="company-info">
                        @php
                            $logoPath = public_path('img/atsaka.webp');
                            $logoExists = file_exists($logoPath);
                            $logoBase64 = '';
                            if ($logoExists) {
                                $logoData = file_get_contents($logoPath);
                                $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
                            }
                        @endphp
                        @if ($logoExists && $logoBase64)
                            <img src="{{ $logoBase64 }}" alt="Indo Atsaka Logo" class="company-logo">
                        @else
                            <div class="company-logo"
                                style="width: 80px; height: 80px; background-color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2); border: 2px solid rgba(255, 255, 255, 0.1);">
                                <div
                                    style="width: 50px; height: 50px; background-color: #E74C3C; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <span style="color: white; font-weight: bold; font-size: 18px;">IA</span>
                                </div>
                            </div>
                        @endif
                        <div style="margin-left: 100px;">
                            <p class="company-name">{{ $template['company_name'] ?? 'PT INDO ATSAKA INDUSTRI' }}
                            </p>
                            <p class="company-tagline">INDUSTRIAL SUPPLIER</p>
                        </div>
                    </div>
                    <div class="invoice-info">
                        <p class="invoice-title">INVOICE</p>
                        <div class="invoice-meta">
                            <div>Nomor: <strong>{{ $invoice->nomor }}</strong></div>
                            @if ($invoice->nomor_so)
                                <div>No. SO: <strong>{{ $invoice->nomor_so }}</strong></div>
                            @endif
                            <div>Tanggal:
                                <strong>{{ \Carbon\Carbon::parse($invoice->tanggal)->format('d/m/Y') }}</strong>
                            </div>
                            @if ($invoice->salesOrder)
                                <div>No. Sales Order: <strong>{{ $invoice->salesOrder->nomor }}</strong></div>
                            @endif
                            @if ($invoice->status)
                                <div>Status Pembayaran:
                                    @php
                                        $statusClass = 'status-default';
                                        $statusLabel = ucfirst(str_replace('_', ' ', $invoice->status));

                                        switch ($invoice->status) {
                                            case 'belum_bayar':
                                                $statusClass = 'status-belum-bayar';
                                                $statusLabel = 'Belum Bayar';
                                                break;
                                            case 'sebagian':
                                                $statusClass = 'status-sebagian';
                                                $statusLabel = 'Sebagian';
                                                break;
                                            case 'lunas':
                                                $statusClass = 'status-lunas';
                                                $statusLabel = 'Lunas';
                                                break;
                                        }
                                    @endphp
                                    <strong class="{{ $statusClass }}">{{ $statusLabel }}</strong>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- Invoice Details -->
            <div class="invoice-details clearfix">
                <div class="invoice-to">
                    <p class="section-title">KEPADA YTH:</p>
                    <p class="customer-name">{{ $invoice->customer->company ?? $invoice->customer->nama }}</p>
                    <div class="customer-details">
                        <p>{{ $invoice->customer->alamat ?? '-' }}</p>
                        @if ($invoice->customer->telepon)
                            <p>Telp: {{ $invoice->customer->telepon }}</p>
                        @endif
                        @if ($invoice->customer->email)
                            <p>Email: {{ $invoice->customer->email }}</p>
                        @endif
                        @if ($invoice->customer->kontak_person)
                            <p>Kontak: {{ $invoice->customer->kontak_person }}
                                @if ($invoice->customer->no_hp_kontak)
                                    ({{ $invoice->customer->no_hp_kontak }})
                                @endif
                            </p>
                        @endif
                    </div>
                </div>
                <div class="payment-method">
                    <p class="section-title">Info Perusahaan</p>
                    <div class="customer-details">
                        <strong>PT INDO ATSAKA INDUSTRI</strong><br>
                        {{ setting('company_address', 'Jl. Condet Raya No. 6 Balekambang') }}<br>
                        Telp. {{ setting('company_phone', '(021) 80876624 - 80876642') }}
                    </div>
                </div>
            </div>
            @if ($invoice->tanggal_jatuh_tempo)
                <div style="border: 2px solid #E74C3C; padding: 10px; margin: 15px 10px; background-color: #fff5f5;">
                    <strong style="color: #E74C3C; font-size: 12px;">Jatuh Tempo:</strong>
                    <span style="font-weight: bold; color: #1F2A44;">
                        {{ \Carbon\Carbon::parse($invoice->tanggal_jatuh_tempo)->format('d/m/Y') }}
                    </span>
                </div>
            @endif
            @if ($invoice->alamat_pengiriman)
                <div style="border: 2px solid #E74C3C; padding: 10px; margin: 15px 10px; background-color: #fff5f5;">
                    <strong style="color: #E74C3C; font-size: 12px;">Alamat Pengiriman:</strong><br>
                    <span style="font-weight: bold; color: #1F2A44;">{{ $invoice->alamat_pengiriman }}</span>
                </div>
            @endif
            <!-- Table Section -->
            <div class="table-section">
                <table class="invoice-table">
                    <thead class="table-header">
                        <tr>
                            <th class="no-col text-center">No</th>
                            <th class="desc-col red-header">Nama Produk</th>
                            <th class="qty-col text-center">Qty</th>
                            <th class="price-col text-center">Satuan</th>
                            <th class="price-col text-center">Harga</th>
                            <th class="price-col text-center">Diskon</th>
                            <th class="total-col text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach ($invoice->details as $detail)
                            <tr class="table-row">
                                <td class="text-center">{{ $no++ }}</td>
                                <td>
                                    <div class="product-name">{{ $detail->produk->nama ?? 'Produk' }}</div>
                                    @if ($detail->deskripsi != $detail->produk->nama)
                                        <p class="product-desc">{{ $detail->deskripsi }}</p>
                                    @endif
                                </td>
                                <td class="text-center">{{ number_format($detail->quantity, 0) }}</td>
                                <td class="text-center">{{ $detail->satuan->nama ?? '-' }}</td>
                                <td class="text-center">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    @if ($detail->diskon_persen > 0)
                                        {{ number_format($detail->diskon_persen, 1) }}%
                                    @endif
                                </td>
                                <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>



            <!-- Totals -->
            @php
                $uangMuka = $invoice->uang_muka_terapkan ?? 0;
                $grandTotal = $invoice->total;
                $totalTagihan = $grandTotal - $uangMuka;
                $nilaiTerbilang = $invoice->total;

                // Jika ada DP dari parameter
                if (isset($dpAmount) && $dpAmount > 0) {
                    $dpSubtotal = $dpAmount;
                    $dpPPN = 0;
                    if ($invoice->ppn > 0) {
                        $ppnRate = $invoice->subtotal > 0 ? $invoice->ppn / $invoice->subtotal : 0;
                        $dpPPN = $dpAmount * $ppnRate;
                    }
                    $totalDP = $dpSubtotal + $dpPPN;
                    $nilaiTerbilang = $totalDP;
                }
            @endphp

            <div class="total-summary clearfix">
                <div class="summary-section">
                    @if (isset($dpAmount) && $dpAmount > 0)
                        {{-- Invoice DP --}}
                        <div class="summary-item clearfix">
                            <span class="label">Uang Muka (DP):</span>
                            <span class="amount">Rp {{ number_format($dpSubtotal, 0, ',', '.') }}</span>
                        </div>
                        @if ($dpPPN > 0)
                            <div class="summary-item clearfix">
                                <span class="label">PPN 11%:</span>
                                <span class="amount">Rp {{ number_format($dpPPN, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="total-final clearfix">
                            <span class="label">Total DP:</span>
                            <span class="amount">Rp {{ number_format($totalDP, 0, ',', '.') }}</span>
                        </div>
                    @elseif ($uangMuka > 0)
                        {{-- Invoice Pelunasan --}}
                        @php
                            $nilaiTerbilang = $totalTagihan;
                            $sisaSetelahUM = $grandTotal - $uangMuka;
                            $pelunasanBersih = $sisaSetelahUM / 1.11;
                            $ppnPelunasan = $pelunasanBersih * 0.11;
                        @endphp
                        <div class="summary-item clearfix">
                            <span class="label">Jmh.Total:</span>
                            <span class="amount">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="summary-item clearfix">
                            <span class="label">Uang Muka:</span>
                            <span class="amount">(Rp {{ number_format($uangMuka, 0, ',', '.') }})</span>
                        </div>
                        <div style="border-top: 1px solid #ddd; margin: 5px 0;"></div>
                        <div class="summary-item clearfix">
                            <span class="label">Pelunasan:</span>
                            <span class="amount">Rp {{ number_format($pelunasanBersih, 0, ',', '.') }}</span>
                        </div>
                        <div class="summary-item clearfix">
                            <span class="label">PPN 11%:</span>
                            <span class="amount">Rp {{ number_format($ppnPelunasan, 0, ',', '.') }}</span>
                        </div>
                        <div style="border-top: 1px solid #ddd; margin: 5px 0;"></div>
                        <div class="total-final clearfix">
                            <span class="label">Total:</span>
                            <span class="amount">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
                        </div>
                    @else
                        {{-- Invoice Normal --}}
                        <div class="summary-item clearfix">
                            <span class="label">Subtotal:</span>
                            <span class="amount">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @if ($invoice->diskon_nominal > 0)
                            <div class="summary-item summary-highlight clearfix">
                                <span class="label">Diskon ({{ number_format($invoice->diskon_persen, 1) }}%):</span>
                                <span class="amount">-Rp
                                    {{ number_format($invoice->diskon_nominal, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        @if ($invoice->ppn > 0)
                            <div class="summary-item clearfix">
                                <span class="label">PPN 11% :</span>
                                <span class="amount">Rp {{ number_format($invoice->ppn, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        @if ($invoice->ongkos_kirim > 0)
                            <div class="summary-item clearfix">
                                <span class="label">Ongkos Kirim:</span>
                                <span class="amount">Rp {{ number_format($invoice->ongkos_kirim, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="total-final clearfix">
                            <span class="label">TOTAL:</span>
                            <span class="amount">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            @if (isset($dpAmount) && $dpAmount > 0)
                {{-- Note untuk DP --}}
                <div
                    style="margin: 10px 10px; padding: 10px; background-color: #f8fafc; border-left: 3px solid #E74C3C;">
                    <strong style="color: #2c3e50; font-size: 10px;">Note:</strong>
                    <div style="font-size: 9px; margin-top: 5px;">
                        @php
                            $totalPO = $invoice->salesOrder->total ?? 0;
                            $persenDP = $totalPO > 0 ? ($dpAmount / $totalPO) * 100 : 0;
                            $nomorPO = $invoice->salesOrder->nomor_po ?? '-';
                        @endphp
                        Pembayaran DP {{ number_format($persenDP, 2) }}% untuk PO: {{ $nomorPO }}<br>
                        Dengan total PO senilai Rp. {{ number_format($totalPO, 0, ',', '.') }}
                    </div>
                </div>
            @endif

            <!-- Text Terbilang -->
            <div style="margin: 15px 10px; color: #1F2A44; font-size: 11px; font-style: italic;">
                <strong>Terbilang:</strong> {{ ucwords(terbilang((int) $nilaiTerbilang)) }} Rupiah
            </div>
            <!-- Notes and Terms Section -->
            <div style="display: table; width: calc(100% - 20px); margin: 10px 10px;">
                <div style="display: table-cell; width: 60%; vertical-align: top; padding-right: 20px;">
                    @if ($invoice->catatan)
                        <div
                            style="margin-bottom: 15px; border-left: 3px solid #E74C3C; padding-left: 10px; background-color: #f8fafc;">
                            <strong style="color: #2c3e50; font-size: 10px;">Catatan:</strong>
                            <p style="font-size: 9px; margin: 2px 0;">{{ $invoice->catatan }}</p>
                        </div>
                    @endif

                    @if ($invoice->syarat_ketentuan)
                        <div
                            style="margin-bottom: 15px; border-left: 3px solid #E74C3C; padding-left: 10px; background-color: #f8fafc;">
                            <strong style="color: #2c3e50; font-size: 10px;">Syarat & Ketentuan:</strong>
                            <div style="font-size: 9px; margin-top: 5px; white-space: pre-line;">
                                {{ $invoice->syarat_ketentuan }}</div>
                        </div>
                    @endif

                    <div
                        style="margin-bottom: 15px; border-left: 3px solid #E74C3C; padding-left: 10px; background-color: #f8fafc;">
                        <strong style="color: #2c3e50; font-size: 10px;">Informasi Pembayaran:</strong>
                        <div style="font-size: 9px; margin-top: 5px;">
                            @if (isset($primaryBank) && $primaryBank)
                                Bank: {{ $primaryBank->nama_bank }}<br>
                                No. Rekening: {{ $primaryBank->nomor_rekening }}<br>
                                Atas Nama: {{ $primaryBank->atas_nama }}
                            @elseif(isset($bankAccounts) && $bankAccounts->isNotEmpty())
                                @php $firstBank = $bankAccounts->first(); @endphp
                                Bank: {{ $firstBank->nama_bank }}<br>
                                No. Rekening: {{ $firstBank->nomor_rekening }}<br>
                                Atas Nama: {{ $firstBank->atas_nama }}
                            @else
                                Bank: {{ setting('company_bank_name', 'Mandiri') }}<br>
                                No. Rekening: {{ setting('company_bank_account', '006.000.301.9563') }}<br>
                                Atas Nama: {{ setting('company_name', 'PT. Sinar Surya Semestaraya') }}
                            @endif

                            @if (isset($bankAccounts) && $bankAccounts->count() > 1)
                                <br><strong style="font-size: 8px; color: #666;">Bank Alternatif:</strong><br>
                                @foreach ($bankAccounts as $bank)
                                    @if (!$primaryBank || $bank->id != $primaryBank->id)
                                        <span style="font-size: 8px;">{{ $bank->nama_bank }}:
                                            {{ $bank->nomor_rekening }} (a.n. {{ $bank->atas_nama }})</span><br>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Signature Section -->
                <div style="display: table-cell; width: 40%; vertical-align: top; text-align: center;">
                    <div
                        style="margin-top: 30px; margin-bottom: 45px; font-weight: bold; color: #1F2A44; font-size: 10px;">
                        Hormat Kami,
                    </div>
                    <div style="height: 60px; margin-bottom: 10px;"></div>
                    <div style="border-top: 1px solid #cbd5e1; width: 80%; margin: 0 auto 10px auto;"></div>
                    <div style="font-weight: bold; color: #1F2A44; font-size: 10px;">
                        {{ $template['direktur_nama'] }}
                    </div>
                    <div style="font-size: 8px; color: #64748b;">PT Indo Atsaka Industri</div>
                </div>
            </div>
        </div>
        <!-- Footer with Thank You -->
        <div class="footer-thank-you">Terima kasih atas kepercayaan Anda</div>
        <div class="footer-decoration"></div>
    </div>
</body>

</html>
