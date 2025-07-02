<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Slip Gaji - {{ $penggajian->karyawan->nama_lengkap }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Watermark background */
        .watermark-bg {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 100%;
            text-align: center;
            z-index: 0;
            opacity: 0.07;
            font-size: 70px;
            font-weight: bold;
            color: #4a6fa5;
            transform: translate(-50%, -50%) rotate(-25deg);
            pointer-events: none;
            user-select: none;
            white-space: nowrap;
        }

        /* Table layouts */
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
            padding: 8px;
            width: 50%;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .detail-table th,
        .detail-table td {
            border: 1px solid #b8c4d6;
            padding: 8px;
            text-align: left;
        }

        .detail-table th {
            background-color: #e8f0fa;
            color: #2c3e50;
            font-weight: bold;
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
            padding: 15px;
        }

        .signature-line {
            border-top: 1px solid #b8c4d6;
            width: 80%;
            margin: 20px auto 10px auto;
        }

        .section-title {
            background-color: #e8f0fa;
            padding: 8px;
            font-weight: bold;
            border-left: 3px solid #4a6fa5;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .amount-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 12px;
            text-align: center;
            border-radius: 5px;
            margin: 10px 0;
        }

        .total-amount {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            margin-top: 30px;
        }

        /* QR Code Styles */
        .qr-signature {
            margin-top: 10px;
            text-align: center;
        }

        .qr-signature .qr-label {
            font-size: 8px;
            color: #666;
            margin-bottom: 3px;
        }

        .qr-signature img {
            border: 1px solid #ddd;
            padding: 2px;
        }

        .qr-code {
            width: 100px;
            height: 100px;
            margin: 5px auto;
            display: block;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px;
            background: white;
        }

        .qr-code-small {
            width: 60px;
            height: 60px;
            margin: 5px auto;
            display: block;
            border: 1px solid #ddd;
            padding: 2px;
            background: white;
        }

        .header-with-qr {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .header-content {
            display: table-cell;
            vertical-align: middle;
            width: 100%;
        }

        .header-qr {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            width: 120px;
        }

        .status-box {
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
            page-break-inside: avoid;
        }

        .status-approved {
            background-color: #f0f9ff;
            border: 1px solid #0ea5e9;
            color: #0369a1;
        }

        .status-paid {
            background-color: #f0fdf4;
            border: 1px solid #22c55e;
            color: #166534;
        }

        .status-draft {
            background-color: #fffbeb;
            border: 1px solid #f59e0b;
            color: #d97706;
        }

        .status-box h4 {
            margin: 0 0 5px 0;
            font-size: 14px;
        }

        .status-box p {
            margin: 0;
            font-size: 11px;
        }

        /* Utility classes */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        /* Print specific styling */
        @page {
            size: A4;
            margin: 1cm;
        }

        .payroll-header {
            text-align: center;
            color: #4a6fa5;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .period-info {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .breakdown-section {
            margin-bottom: 20px;
        }

        .breakdown-title {
            background-color: #4a6fa5;
            color: white;
            padding: 8px;
            font-weight: bold;
            text-align: center;
        }

        .positive-amount {
            color: #059669;
            font-weight: bold;
        }

        .negative-amount {
            color: #dc2626;
            font-weight: bold;
        }

        .neutral-amount {
            color: #374151;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="watermark-bg">{{ strtoupper(setting('company_name', 'SINAR SURYA SEMESTARAYA')) }}</div>

    <!-- Header Section with QR Code -->
    <div class="header-with-qr">
        <div class="header-content">
            <table class="header-table">
                <tr>
                    <td style="width: 50%; vertical-align: middle;">
                        <img src="{{ public_path('img/logo_nama3.png') }}" alt="Sinar Surya Logo"
                            onerror="this.src='{{ public_path('img/logo-default.png') }}';" style="height: 60px;">
                    </td>
                    <td style="width: 50%; text-align: right; vertical-align: middle;">
                        <div class="payroll-header">SLIP GAJI</div>
                        <div class="period-info">
                            Periode: {{ $monthName }} {{ $penggajian->tahun }}
                        </div>

                    </td>
                </tr>
            </table>
        </div>

    </div>

    <!-- Employee Information -->
    <table class="info-table">
        <tr>
            <td>
                <div class="section-title">Informasi Perusahaan</div>
                <div style="padding: 8px;">
                    <strong>{{ setting('company_name', 'PT. SINAR SURYA SEMESTARAYA') }}</strong><br>
                    {{ setting('company_address', 'Jl. Condet Raya No. 6 Balekambang') }}<br>
                    {{ setting('company_city', 'Jakarta Timur') }} {{ setting('company_postal_code', '13530') }}<br>
                    Telp. {{ setting('company_phone', '(021) 80876624 - 80876642') }}<br>
                    E-mail: {{ setting('company_email', 'admin@kliksinarsurya.com') }}
                </div>
            </td>
            <td>
                <div class="section-title">Informasi Karyawan</div>
                <div style="padding: 8px;">
                    <strong>{{ $penggajian->karyawan->nama_lengkap }}</strong><br>
                    NIK: {{ $penggajian->karyawan->nik ?? '-' }}<br>
                    @if ($penggajian->karyawan->posisi)
                        Posisi: {{ $penggajian->karyawan->posisi }}<br>
                    @endif
                    @if ($penggajian->karyawan->department)
                        Departemen: {{ $penggajian->karyawan->department->nama }}<br>
                    @endif
                    @if ($penggajian->karyawan->email)
                        Email: {{ $penggajian->karyawan->email }}<br>
                    @endif
                    @if ($penggajian->karyawan->telepon)
                        Telepon: {{ $penggajian->karyawan->telepon }}
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Salary Breakdown -->
    <div class="breakdown-section">
        <div class="breakdown-title">RINCIAN GAJI</div>
        <table class="detail-table">
            <thead>
                <tr>
                    <th width="50%">Komponen</th>
                    <th width="50%" class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <!-- Income Section -->
                <tr style="background-color: #f8fffe;">
                    <td colspan="2" class="font-bold" style="color: #059669;">PENDAPATAN</td>
                </tr>
                <tr>
                    <td>Gaji Pokok</td>
                    <td class="text-right positive-amount">Rp {{ number_format($penggajian->gaji_pokok, 0, ',', '.') }}
                    </td>
                </tr>
                @if ($penggajian->tunjangan > 0)
                    <tr>
                        <td>Tunjangan</td>
                        <td class="text-right positive-amount">Rp
                            {{ number_format($penggajian->tunjangan, 0, ',', '.') }}</td>
                    </tr>
                @endif
                @if ($penggajian->bonus > 0)
                    <tr>
                        <td>Bonus</td>
                        <td class="text-right positive-amount">Rp {{ number_format($penggajian->bonus, 0, ',', '.') }}
                        </td>
                    </tr>
                @endif
                @if ($penggajian->lembur > 0)
                    <tr>
                        <td>Lembur</td>
                        <td class="text-right positive-amount">Rp {{ number_format($penggajian->lembur, 0, ',', '.') }}
                        </td>
                    </tr>
                @endif

                @php
                    $totalPendapatan =
                        $penggajian->gaji_pokok + $penggajian->tunjangan + $penggajian->bonus + $penggajian->lembur;
                @endphp
                <tr style="border-top: 2px solid #059669;">
                    <td class="font-bold">Total Pendapatan</td>
                    <td class="text-right font-bold positive-amount">Rp
                        {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                </tr>

                @if ($penggajian->potongan > 0)
                    <!-- Deduction Section -->
                    <tr style="background-color: #fef8f8;">
                        <td colspan="2" class="font-bold" style="color: #dc2626;">POTONGAN</td>
                    </tr>
                    <tr>
                        <td>Total Potongan</td>
                        <td class="text-right negative-amount">- Rp
                            {{ number_format($penggajian->potongan, 0, ',', '.') }}</td>
                    </tr>
                @endif

                <!-- Total Section -->
                <tr style="background-color: #f3f4f6; border-top: 3px solid #4a6fa5;">
                    <td class="font-bold" style="font-size: 14px;">TOTAL GAJI BERSIH</td>
                    <td class="text-right font-bold" style="font-size: 14px; color: #2c3e50;">
                        Rp {{ number_format($penggajian->total_gaji, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Payment Information -->
    @if ($penggajian->tanggal_bayar)
        <div style="border: 1px dashed #b8c4d6; padding: 10px; margin-bottom: 15px; background-color: #f8fafc;">
            <strong style="color: #2c3e50;">Informasi Pembayaran:</strong><br>
            <div style="margin-top: 5px;">
                <strong>Tanggal Bayar:</strong>
                {{ \Carbon\Carbon::parse($penggajian->tanggal_bayar)->format('d/m/Y') }}<br>
                <strong>Metode Pembayaran:</strong> {{ ucfirst($penggajian->metode_pembayaran) }}
            </div>
        </div>
    @endif

    <!-- Signatures with QR Codes -->
    <table class="signature-table">
        <tr>
            <td>
                <div><strong style="color: #2c3e50;">Dibuat oleh:</strong></div>

                {{-- QR Code for Creator (HR System) --}}
                @if (isset($qrCodes['created_qr']) && $qrCodes['created_qr'])
                    <div class="qr-signature">
                        <div class="qr-label">Tanda Tangan Digital</div>
                        <img src="{{ $qrCodes['created_qr'] }}" alt="Creator QR Code" class="qr-code-small">
                    </div>
                @else
                    <div class="qr-signature">
                        <div class="qr-label">Sistem HR</div>
                        <div
                            style="width: 60px; height: 60px; border: 1px dashed #ccc; display: inline-block; line-height: 60px; font-size: 10px; color: #999;">
                            HR
                        </div>
                    </div>
                @endif

                <div class="signature-line"></div>
                <div><strong style="color: #2c3e50;">HR Department</strong></div>
                <div style="color: #7f8c8d;">Human Resources</div>
                <div style="font-size:10px;">
                    {{ \Carbon\Carbon::parse($penggajian->created_at)->format('d/m/Y H:i') }}
                </div>
                <div style="font-size:10px; color: #666;">
                    Sistem ERP
                </div>
            </td>

            @if ($isApproved && $approvedBy)
                <td>
                    <div><strong style="color: #2c3e50;">Disetujui oleh:</strong></div>

                    {{-- QR Code for Approver --}}
                    @if (isset($qrCodes['processed_qr']) && $qrCodes['processed_qr'])
                        <div class="qr-signature">
                            <div class="qr-label">Tanda Tangan Digital</div>
                            <img src="{{ $qrCodes['processed_qr'] }}" alt="Approver QR Code" class="qr-code-small">
                        </div>
                    @endif

                    <div class="signature-line"></div>
                    <div><strong style="color: #2c3e50;">{{ $approvedBy->name }}</strong></div>
                    <div style="color: #7f8c8d;">Manager/Direktur</div>
                    <div style="font-size:10px;">
                        {{ $penggajian->updated_at->format('d/m/Y H:i') }}
                    </div>
                    <div style="font-size:10px; color: #666;">
                        {{ $approvedBy->email ?? '' }}
                    </div>
                </td>
            @else
                <td>
                    <div><strong style="color: #2c3e50;">Disetujui oleh:</strong></div>
                    <div class="signature-line"></div>

                    <div class="qr-signature">
                        <div class="qr-label">Menunggu Tanda Tangan</div>
                        <div
                            style="width: 60px; height: 60px; border: 1px dashed #ccc; display: inline-block; line-height: 60px; font-size: 10px; color: #999;">
                            QR
                        </div>
                    </div>

                    <div><strong style="color: #2c3e50;">Menunggu Persetujuan</strong></div>
                    <div style="color: #7f8c8d;">Manager/Direktur</div>
                    <div style="font-size:10px;">Tanggal: ___/___/______</div>
                </td>
            @endif
        </tr>
    </table>

    {{-- Status Box --}}
    @if ($penggajian->status === 'dibayar')
        <div class="status-box status-paid">
            <h4><strong>Status: TELAH DIBAYAR</strong></h4>
            <p>
                Gaji telah dibayar pada
                {{ $penggajian->tanggal_bayar ? \Carbon\Carbon::parse($penggajian->tanggal_bayar)->format('d F Y') : '-' }}
                melalui {{ ucfirst($penggajian->metode_pembayaran) }}.
            </p>
        </div>
    @elseif ($penggajian->status === 'disetujui')
        <div class="status-box status-approved">
            <h4><strong>Status: TELAH DISETUJUI</strong></h4>
            <p>
                Slip gaji telah disetujui dan menunggu proses pembayaran.
            </p>
        </div>
    @elseif ($penggajian->status === 'draft')
        <div class="status-box status-draft">
            <h4><strong>Status: DRAFT</strong></h4>
            <p>
                Slip gaji masih dalam status draft dan belum disetujui.
            </p>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Slip gaji dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y H:i:s') }} |
            {{ setting('company_name', 'PT Sinar Surya Semestaraya') }} ERP System</p>
        <div style="font-size: 9px; color: #aaa; margin-top: 5px;">
            Dokumen ini adalah slip gaji resmi dan sah secara hukum. Harap simpan untuk keperluan administrasi.
        </div>
    </div>
</body>

</html>
