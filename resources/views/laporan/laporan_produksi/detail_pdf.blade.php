<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Detail Produksi #{{ $produksi->nomor }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            gap: 15px;
            flex-wrap: nowrap;
        }

        .company-logo {
            max-width: 100px;
            max-height: 80px;
            width: auto;
            height: auto;
            object-fit: contain;
            display: block;
            flex-shrink: 0;
        }

        .company-info {
            text-align: center;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #1a202c;
            margin-bottom: 3px;
        }

        .company-tagline {
            font-size: 11px;
            color: #718096;
            font-style: italic;
        }

        .report-title {
            font-size: 16px;
            font-weight: bold;
            color: #2d3748;
            margin-top: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header h1 {
            margin: 0;
            padding: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 12px;
        }

        .meta-info {
            margin-bottom: 20px;
        }

        .meta-info table {
            width: 100%;
        }

        .meta-info td {
            padding: 3px 0;
        }

        .meta-info .label {
            font-weight: bold;
            width: 150px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            background-color: #f2f2f2;
            padding: 8px;
            font-weight: bold;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .info-item {
            margin-bottom: 10px;
        }

        .info-item .label {
            font-weight: bold;
            color: #666;
            font-size: 11px;
        }

        .info-item .value {
            margin-top: 3px;
        }

        .status {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-selesai {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-proses {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-batal {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        .status-menunggu {
            background-color: #fef3c7;
            color: #92400e;
        }

        .notes {
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 4px;
            margin-top: 15px;
        }

        .notes .label {
            font-weight: bold;
            color: #666;
            font-size: 11px;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        @php
            $logoSrc = null;

            // Try company logo first
            if (isset($company) && $company && $company->logo) {
                $logoPath = public_path('storage/' . $company->logo);
                if (file_exists($logoPath)) {
                    $logoData = base64_encode(file_get_contents($logoPath));
                    $logoMimeType = mime_content_type($logoPath);
                    $logoSrc = 'data:' . $logoMimeType . ';base64,' . $logoData;
                }
            }

            // Fallback to default logo
            if (!$logoSrc) {
                $defaultLogoPath = public_path('img/logo-sinar-surya.png');
                if (file_exists($defaultLogoPath)) {
                    $defaultLogoData = base64_encode(file_get_contents($defaultLogoPath));
                    $defaultLogoMimeType = mime_content_type($defaultLogoPath);
                    $logoSrc = 'data:' . $defaultLogoMimeType . ';base64,' . $defaultLogoData;
                }
            }
        @endphp

        <div class="logo-container">
            @if ($logoSrc)
                <img src="{{ $logoSrc }}" alt="Logo {{ $company->nama ?? 'Perusahaan' }}" class="company-logo">
            @endif

            <div class="company-info">
                <div class="company-name">{{ $company->nama ?? 'PT SINAR SURYA SEMESTARAYA' }}</div>
                {{-- <div class="company-tagline">{{ $company->footer_text ?? 'Bersama Membangun Masa Depan' }}</div> --}}
            </div>
        </div>

        <div class="report-title">DETAIL PRODUKSI #{{ $produksi->nomor }}</div>
    </div>

    <div class="meta-info">
        <table>
            <tr>
                <td class="label">Tanggal Cetak</td>
                <td>: {{ now()->format('d M Y H:i') }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Informasi Produksi</div>
        <div class="info-grid">
            <div>
                <div class="info-item">
                    <div class="label">Nomor Produksi</div>
                    <div class="value"><strong>{{ $produksi->nomor }}</strong></div>
                </div>
                <div class="info-item">
                    <div class="label">Tanggal Produksi</div>
                    <div class="value">{{ \Carbon\Carbon::parse($produksi->tanggal)->format('d M Y') }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Status</div>
                    <div class="value">
                        @if ($produksi->status == 'selesai')
                            <span class="status status-selesai">Selesai</span>
                        @elseif($produksi->status == 'proses')
                            <span class="status status-proses">Proses</span>
                        @elseif($produksi->status == 'batal')
                            <span class="status status-batal">Dibatalkan</span>
                        @elseif($produksi->status == 'menunggu')
                            <span class="status status-menunggu">Menunggu</span>
                        @endif
                    </div>
                </div>
            </div>
            <div>
                <div class="info-item">
                    <div class="label">Produk</div>
                    <div class="value"><strong>{{ $produksi->produk_nama }}</strong></div>
                </div>
                <div class="info-item">
                    <div class="label">Kode Produk</div>
                    <div class="value">{{ $produksi->produk_kode }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Jumlah Produksi</div>
                    <div class="value"><strong>{{ number_format($produksi->jumlah, 0, ',', '.') }}</strong></div>
                </div>
            </div>
        </div>

        @if ($produksi->catatan)
            <div class="notes">
                <div class="label">Catatan</div>
                <div>{{ $produksi->catatan }}</div>
            </div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Informasi Tambahan</div>
        <div class="info-grid">
            <div>
                <div class="info-item">
                    <div class="label">Petugas</div>
                    <div class="value">{{ $produksi->nama_petugas ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Tanggal Dibuat</div>
                    <div class="value">
                        {{ isset($produksi->created_at) ? \Carbon\Carbon::parse($produksi->created_at)->format('d M Y H:i') : '-' }}
                    </div>
                </div>
            </div>
            <div>
                <div class="info-item">
                    <div class="label">Terakhir Diupdate</div>
                    <div class="value">
                        {{ isset($produksi->updated_at) ? \Carbon\Carbon::parse($produksi->updated_at)->format('d M Y H:i') : '-' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Dokumen ini dihasilkan oleh Sistem ERP PT Sinar Surya pada {{ now()->format('d M Y H:i:s') }}</p>
    </div>
</body>

</html>
