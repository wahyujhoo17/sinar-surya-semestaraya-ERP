<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print - Uang Muka Penjualan {{ $uangMuka->nomor }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .print-container {
            max-width: 210mm;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            position: relative;
        }

        /* Watermark background for print preview */
        .watermark-bg {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            text-align: center;
            z-index: 0;
            opacity: 0.03;
            font-size: 60px;
            font-weight: bold;
            color: #4a6fa5;
            transform: translate(-50%, -50%) rotate(-25deg);
            pointer-events: none;
            user-select: none;
            white-space: nowrap;
        }

        .print-actions {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            margin: 0 5px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: #4a6fa5;
            color: white;
        }

        .btn-primary:hover {
            background-color: #3d5a87;
            transform: translateY(-1px);
        }

        .btn-success {
            background-color: #059669;
            color: white;
        }

        .btn-success:hover {
            background-color: #047857;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #4b5563;
            transform: translateY(-1px);
        }

        /* Table-based layout similar to PDF */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #4a6fa5;
            margin-bottom: 20px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info-table td {
            vertical-align: top;
            padding: 5px;
            width: 50%;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .details-table th,
        .details-table td {
            border: 1px solid #b8c4d6;
            padding: 8px;
            text-align: left;
        }

        .details-table th {
            background-color: #e8f0fa;
            color: #2c3e50;
            font-weight: bold;
        }

        .section-title {
            background-color: #e8f0fa;
            padding: 8px;
            font-weight: bold;
            border-left: 3px solid #4a6fa5;
            color: #2c3e50;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary-table {
            border-collapse: collapse;
            width: 40%;
            margin-left: 60%;
            margin-bottom: 15px;
        }

        .summary-table td {
            padding: 5px;
        }

        .total-row {
            font-weight: bold;
            border-top: 1px solid #4a6fa5;
            color: #2c3e50;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .signature-table td {
            width: 50%;
            vertical-align: bottom;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #b8c4d6;
            width: 80%;
            margin: 50px auto 10px auto;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-confirmed {
            background-color: #D1FAE5;
            color: #059669;
        }

        .status-applied {
            background-color: #DBEAFE;
            color: #1E40AF;
        }

        .status-partially-applied {
            background-color: #FEF3C7;
            color: #D97706;
        }

        .text-amount {
            text-transform: uppercase;
            font-style: italic;
            font-size: 11px;
            margin-top: 5px;
            color: #4a6fa5;
        }

        .footer {
            text-align: center;
            margin-top: 45px;
            border-top: 1.5px solid #e0e6ed;
            padding-top: 22px;
            background-color: #f9fafb;
        }

        .footer-text {
            font-size: 9.5px;
            color: #6b7280;
            margin-top: 15px;
            padding-bottom: 12px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #4a6fa5;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .header-table td {
            padding: 10px;
            vertical-align: middle;
        }

        .company-logo {
            width: 25%;
            text-align: left;
        }

        .company-info {
            width: 50%;
            text-align: center;
        }

        .document-info {
            width: 25%;
            text-align: right;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #4a6fa5;
            margin-bottom: 5px;
        }

        .company-address {
            font-size: 10px;
            color: #666;
            margin-bottom: 2px;
        }

        .document-title {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-success {
            background-color: #059669;
            color: white;
        }

        .btn-success:hover {
            background-color: #047857;
            transform: translateY(-1px);
        }

        .info-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .info-group {
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #2563eb;
        }

        .info-row {
            margin-bottom: 12px;
            display: flex;
            align-items: flex-start;
        }

        .label {
            font-weight: 600;
            color: #374151;
            min-width: 120px;
            margin-right: 15px;
        }

        .value {
            color: #1f2937;
            flex: 1;
        }

        .amount-section {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            border-radius: 12px;
            padding: 30px;
            margin: 30px 0;
            text-align: center;
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.2);
        }

        .amount-label {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 15px;
            opacity: 0.9;
            letter-spacing: 1px;
        }

        .amount-value {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 15px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .amount-words {
            font-style: italic;
            opacity: 0.9;
            border-top: 1px solid rgba(255, 255, 255, 0.3);
            padding-top: 20px;
            margin-top: 20px;
            font-size: 14px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .details-table th,
        .details-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .details-table th {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            font-weight: 600;
            color: #374151;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
        }

        .details-table tr:last-child td {
            border-bottom: none;
        }

        .details-table tr:hover {
            background-color: #f8fafc;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-confirmed {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-applied {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-partially-applied {
            background-color: #fef3c7;
            color: #92400e;
        }

        .signature-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            margin-top: 50px;
        }

        .signature-box {
            text-align: center;
            padding: 20px;
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            height: 100px;
            position: relative;
            background-color: #f9fafb;
        }

        .signature-label {
            position: absolute;
            bottom: 10px;
            left: 0;
            right: 0;
            font-weight: 600;
            font-size: 12px;
            color: #4b5563;
        }

        .notes {
            background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
            border-left: 5px solid #f59e0b;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
        }

        .notes-title {
            font-weight: 600;
            margin-bottom: 15px;
            color: #92400e;
            display: flex;
            align-items: center;
        }

        .notes-title::before {
            content: "⚠️";
            margin-right: 8px;
        }

        .footer-info {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 10px;
            color: #6b7280;
        }

        /* Print Styles */
        @media print {
            body {
                background-color: white;
                padding: 0;
            }

            .print-container {
                box-shadow: none;
                padding: 0;
                max-width: none;
            }

            .print-actions {
                display: none !important;
            }

            .info-section {
                grid-template-columns: 1fr 1fr;
            }

            .signature-box {
                height: 80px;
                background-color: transparent;
            }

            .amount-section {
                box-shadow: none;
            }

            .details-table {
                box-shadow: none;
            }
        }

        @page {
            margin: 20mm;
            size: A4;
        }
    </style>
</head>

<body>
    <!-- Print Actions -->
    <div class="print-actions">
        <button onclick="window.print()" class="btn btn-primary">
            🖨️ Print
        </button>
        <a href="{{ route('keuangan.uang-muka-penjualan.exportPdf', $uangMuka->id) }}" class="btn btn-success">
            📁 Download PDF
        </a>
        <a href="{{ route('keuangan.uang-muka-penjualan.show', $uangMuka->id) }}" class="btn btn-secondary">
            ← Kembali
        </a>
    </div>

    <div class="print-container">
        <div class="watermark-bg">{{ strtoupper(setting('company_name', 'SINAR SURYA SEMESTARAYA')) }}</div>

        <!-- Header Section -->
        <table class="header-table">
            <tr style="margin-bottom: 10px;">
                <td style="width: 50%; vertical-align: middle;">
                    <img src="{{ public_path('img/logo_nama3.png') }}" alt="Sinar Surya Logo"
                        onerror="this.src='{{ public_path('img/logo-default.png') }}';" style="height: 60px;">
                </td>
                <td style="width: 50%; text-align: right; vertical-align: middle;">
                    <h2 style="color: #4a6fa5; margin: 0 0 5px 0;">UANG MUKA PENJUALAN</h2>
                    <div>
                        <strong>Nomor:</strong> {{ $uangMuka->nomor }}<br>
                        <strong>Tanggal:</strong> {{ $uangMuka->tanggal->format('d/m/Y') }}<br>
                        @if ($uangMuka->nomor_referensi)
                            <strong>No. Referensi:</strong> {{ $uangMuka->nomor_referensi }}<br>
                        @endif
                        @if ($uangMuka->salesOrder)
                            <strong>Sales Order:</strong> {{ $uangMuka->salesOrder->nomor }}
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <!-- Company and Customer Info Section -->
        <table class="info-table">
            <tr>
                <td>
                    <div class="section-title">Info Perusahaan</div>
                    <div style="padding: 5px;">
                        <strong>{{ setting('company_name', 'PT. SINAR SURYA SEMESTARAYA') }}</strong><br>
                        {{ setting('company_address', 'Jl. Condet Raya No. 6 Balekambang') }}<br>
                        {{ setting('company_city', 'Jakarta Timur') }}
                        {{ setting('company_postal_code', '13530') }}<br>
                        Telp. {{ setting('company_phone', '(021) 80876624 - 80876642') }}<br>
                        E-mail: {{ setting('company_email', 'admin@kliksinarsurya.com') }}<br>
                        @if (setting('company_email_2'))
                            {{ setting('company_email_2') }}<br>
                        @endif
                        @if (setting('company_email_3'))
                            {{ setting('company_email_3') }}
                        @endif
                    </div>
                </td>
                <td>
                    <div class="section-title">Customer</div>
                    <div style="padding: 5px;">
                        <strong>{{ $uangMuka->customer->nama ?: $uangMuka->customer->company }}</strong><br>
                        @if ($uangMuka->customer->nama && $uangMuka->customer->company)
                            {{ $uangMuka->customer->company }}<br>
                        @endif
                        {{ $uangMuka->customer->alamat ?? '-' }}<br>
                        @if ($uangMuka->customer->telepon)
                            Telp: {{ $uangMuka->customer->telepon }}<br>
                        @endif
                        @if ($uangMuka->customer->email)
                            Email: {{ $uangMuka->customer->email }}<br>
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <!-- Payment Details Table -->
        <table class="details-table">
            <thead>
                <tr>
                    <th width="25%">Keterangan</th>
                    <th width="75%">Detail</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Status</strong></td>
                    <td>
                        <span
                            class="status-badge 
                            @if ($uangMuka->status === 'confirmed') status-confirmed
                            @elseif($uangMuka->status === 'applied') status-applied
                            @elseif($uangMuka->status === 'partially_applied') status-partially-applied @endif">
                            @if ($uangMuka->status === 'confirmed')
                                Dikonfirmasi
                            @elseif($uangMuka->status === 'applied')
                                Teraplikasi
                            @elseif($uangMuka->status === 'partially_applied')
                                Sebagian Teraplikasi
                            @else
                                {{ ucfirst($uangMuka->status) }}
                            @endif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td><strong>Metode Pembayaran</strong></td>
                    <td>
                        @if ($uangMuka->metode_pembayaran === 'kas')
                            <strong>Pembayaran Kas</strong>
                            @if ($uangMuka->kas)
                                <br><small style="color: #666;">Kas: {{ $uangMuka->kas->nama }}</small>
                            @endif
                        @else
                            <strong>Transfer Bank</strong>
                            @if ($uangMuka->rekeningBank)
                                <br><small style="color: #666;">Bank: {{ $uangMuka->rekeningBank->nama_bank }}</small>
                                <br><small style="color: #666;">No. Rekening:
                                    {{ $uangMuka->rekeningBank->nomor_rekening }}</small>
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><strong>Jumlah Diterima</strong></td>
                    <td><strong>Rp {{ number_format($uangMuka->jumlah, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td><strong>Jumlah Tersedia</strong></td>
                    <td><strong style="color: #059669;">Rp
                            {{ number_format($uangMuka->jumlah_tersedia, 0, ',', '.') }}</strong></td>
                </tr>
                @if ($uangMuka->aplikasi()->exists())
                    <tr>
                        <td><strong>Sudah Diaplikasikan</strong></td>
                        <td><strong style="color: #dc2626;">Rp
                                {{ number_format($uangMuka->aplikasi->sum('jumlah_aplikasi'), 0, ',', '.') }}</strong>
                        </td>
                    </tr>
                @endif
                @if ($uangMuka->keterangan)
                    <tr>
                        <td><strong>Keterangan</strong></td>
                        <td>{{ $uangMuka->keterangan }}</td>
                    </tr>
                @endif
                <tr>
                    <td><strong>Dibuat oleh</strong></td>
                    <td>{{ $uangMuka->user->name }} pada {{ $uangMuka->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            </tbody>
        </table>

        <div style="display: flex; justify-content: space-between;">
            <div style="width: 60%;">
                @if ($uangMuka->keterangan)
                    <div
                        style="margin-bottom: 15px; border-left: 3px solid #4a6fa5; padding-left: 10px; background-color: #f8fafc;">
                        <strong style="color: #2c3e50;">Catatan:</strong>
                        <p>{{ $uangMuka->keterangan }}</p>
                    </div>
                @endif

                <div class="text-amount">
                    <strong>Terbilang:</strong> {{ ucwords(terbilang((int) $uangMuka->jumlah)) }} Rupiah
                </div>
            </div>

            <!-- Summary Table -->
            <table class="summary-table">
                <tr>
                    <td>Jumlah Uang Muka</td>
                    <td class="text-right">Rp {{ number_format($uangMuka->jumlah, 0, ',', '.') }}</td>
                </tr>
                @if ($uangMuka->aplikasi()->exists())
                    <tr>
                        <td>Sudah Digunakan</td>
                        <td class="text-right">Rp
                            {{ number_format($uangMuka->aplikasi->sum('jumlah_aplikasi'), 0, ',', '.') }}</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td><strong>Sisa Tersedia</strong></td>
                    <td class="text-right"><strong>Rp
                            {{ number_format($uangMuka->jumlah_tersedia, 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>

        <!-- Application History -->
        @if ($uangMuka->aplikasi()->exists())
            <div style="margin-top: 25px;">
                <div class="section-title">Riwayat Aplikasi Uang Muka</div>
                <table class="details-table" style="margin-top: 10px;">
                    <thead>
                        <tr>
                            <th width="15%">Tanggal</th>
                            <th width="25%">No. Invoice</th>
                            <th width="30%">Customer</th>
                            <th width="15%">Jumlah</th>
                            <th width="15%">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($uangMuka->aplikasi as $aplikasi)
                            <tr>
                                <td>{{ $aplikasi->tanggal_aplikasi->format('d/m/Y') }}</td>
                                <td><strong>{{ $aplikasi->invoice->nomor ?? '-' }}</strong></td>
                                <td>{{ $aplikasi->invoice->customer->nama ?? ($aplikasi->invoice->customer->company ?? '-') }}
                                </td>
                                <td class="text-right"><strong>Rp
                                        {{ number_format($aplikasi->jumlah_aplikasi, 0, ',', '.') }}</strong></td>
                                <td style="font-size: 10px;">{{ $aplikasi->keterangan ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Terms and Conditions -->
        <div
            style="margin-bottom: 15px; border-left: 3px solid #4a6fa5; padding-left: 10px; background-color: #f8fafc;">
            <strong style="color: #2c3e50;">Catatan Penting:</strong>
            <ol style="margin-top: 5px; padding-left: 20px;">
                <li>Bukti ini merupakan tanda terima uang muka penjualan yang sah dari customer</li>
                <li>Uang muka akan diaplikasikan ke invoice sesuai dengan ketentuan dan kesepakatan yang berlaku</li>
                <li>Harap simpan bukti ini dengan baik sebagai dokumentasi pembayaran</li>
                <li>Untuk pertanyaan mengenai uang muka ini, silakan hubungi bagian keuangan</li>
                @if ($uangMuka->status === 'confirmed')
                    <li><strong>Status saat ini:</strong> Uang muka telah dikonfirmasi dan siap untuk digunakan</li>
                @elseif($uangMuka->status === 'partially_applied')
                    <li><strong>Status saat ini:</strong> Sebagian uang muka telah diaplikasikan ke invoice</li>
                @elseif($uangMuka->status === 'applied')
                    <li><strong>Status saat ini:</strong> Seluruh uang muka telah diaplikasikan ke invoice</li>
                @endif
            </ol>
        </div>

        <!-- Signatures -->
        <table class="signature-table">
            <tr>
                <td>
                    <div class="signature-line"></div>
                    <div><strong style="color: #2c3e50;">{{ $uangMuka->user->name ?? 'Admin' }}</strong></div>
                    <div style="color: #7f8c8d;">Finance</div>
                </td>
                <td>
                    <div class="signature-line"></div>
                    <div><strong
                            style="color: #2c3e50;">{{ $uangMuka->customer->nama ?? $uangMuka->customer->company }}</strong>
                    </div>
                    <div style="color: #7f8c8d;">Customer</div>
                </td>
            </tr>
        </table>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-text">
                <p>Dokumen ini dicetak secara digital pada {{ now()->format('d M Y, H:i') }} WIB |
                    {{ setting('company_name', 'PT. SINAR SURYA SEMESTARAYA') }}</p>
            </div>
        </div>
    </div>

    <script>
        // Auto-focus print dialog when accessed via print parameter
        if (window.location.search.includes('print=1')) {
            window.onload = function() {
                setTimeout(() => {
                    window.print();
                }, 500);
            };
        }
    </script>
</body>

</html>
