<table>
    <tr>
        <td colspan="16" style="font-size: 16px; font-weight: bold; text-align: center;">LAPORAN PEMBELIAN DETAIL</td>
    </tr>
    <tr>
        <td colspan="16" style="font-size: 14px; font-weight: bold; text-align: center;">PT. SINAR SURYA SEMESTARAYA
        </td>
    </tr>
    <tr>
        <td colspan="16" style="font-size: 12px; font-weight: bold; text-align: center;">
            Periode: {{ \Carbon\Carbon::parse($filters['tanggal_awal'])->format('d/m/Y') }} -
            {{ \Carbon\Carbon::parse($filters['tanggal_akhir'])->format('d/m/Y') }}
        </td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <th
            style="background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            No Faktur</th>
        <th
            style="background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            Tanggal</th>
        <th
            style="background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            Supplier</th>
        <th
            style="background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            Kode Produk</th>
        <th
            style="background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            Nama Produk</th>
        <th
            style="background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            Qty</th>
        <th
            style="background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            Satuan</th>
        <th
            style="background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            Harga Satuan</th>
        <th
            style="background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            Disc (%)</th>
        <th
            style="background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            Subtotal</th>
        <th
            style="background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            PPN (%)</th>
        <th
            style="background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            PPN (Rp)</th>
        <th
            style="background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            Total PO</th>
        <th
            style="background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            Dibayar</th>
        <th
            style="background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            Status</th>
        <th
            style="background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            Petugas</th>
    </tr>

    @foreach ($dataPembelian as $po)
        @php
            $statusLabel = match ($po->status_pembayaran ?? $po->status) {
                'lunas' => 'Lunas',
                'sebagian' => 'Sebagian',
                'belum_bayar' => 'Belum Bayar',
                default => ucfirst($po->status_pembayaran ?? ($po->status ?? '-')),
            };
            $total = is_numeric($po->total) ? (float) $po->total : 0;
            $totalBayar = is_numeric($po->total_bayar) ? (float) $po->total_bayar : 0;
            $sisa = $total - $totalBayar;

            // Hitung PPN
            $ppnPersen = floatval($po->ppn ?? 0);
            $ppnNominal = ($ppnPersen / 100) * floatval($po->subtotal ?? 0);
        @endphp
        @if ($po->details && $po->details->count() > 0)
            @foreach ($po->details as $index => $detail)
                <tr style="border-bottom: {{ $loop->last ? '2px solid #000000' : '1px solid #D1D5DB' }};">
                    @if ($index === 0)
                        <td rowspan="{{ $po->details->count() }}"
                            style="border: 1px solid #666666; vertical-align: top; border-left: 2px solid #000000;">
                            {{ $po->nomor_faktur ?? $po->nomor }}</td>
                        <td rowspan="{{ $po->details->count() }}"
                            style="border: 1px solid #666666; vertical-align: top; text-align: center;">
                            {{ \Carbon\Carbon::parse($po->tanggal)->format('d/m/Y') }}</td>
                        <td rowspan="{{ $po->details->count() }}"
                            style="border: 1px solid #666666; vertical-align: top;">
                            {{ $po->supplier->nama ?? ($po->supplier_nama ?? '-') }}</td>
                    @endif
                    <td style="border: 1px solid #D1D5DB;">{{ $detail->produk->kode ?? '-' }}</td>
                    <td style="border: 1px solid #D1D5DB;">{{ $detail->produk->nama ?? '-' }}</td>
                    <td style="border: 1px solid #D1D5DB; text-align: right;">
                        {{ $detail->quantity }}</td>
                    <td style="border: 1px solid #D1D5DB; text-align: center;">
                        {{ $detail->produk->satuan->nama ?? '-' }}</td>
                    <td style="border: 1px solid #D1D5DB; text-align: right;">
                        {{ $detail->harga }}</td>
                    <td style="border: 1px solid #D1D5DB; text-align: right;">
                        {{ $detail->diskon_persen }}</td>
                    <td style="border: 1px solid #D1D5DB; text-align: right;">
                        {{ $detail->subtotal }}</td>
                    @if ($index === 0)
                        <td rowspan="{{ $po->details->count() }}"
                            style="border: 1px solid #666666; vertical-align: top; text-align: right;">
                            {{ $ppnPersen > 0 ? $ppnPersen / 100 : 0 }}</td>
                        <td rowspan="{{ $po->details->count() }}"
                            style="border: 1px solid #666666; vertical-align: top; text-align: right;">
                            {{ $ppnNominal > 0 ? $ppnNominal : 0 }}</td>
                        <td rowspan="{{ $po->details->count() }}"
                            style="border: 1px solid #666666; vertical-align: top; text-align: right;">
                            {{ $total }}</td>
                        <td rowspan="{{ $po->details->count() }}"
                            style="border: 1px solid #666666; vertical-align: top; text-align: right;">
                            {{ $totalBayar }}</td>
                        <td rowspan="{{ $po->details->count() }}"
                            style="border: 1px solid #666666; vertical-align: top; text-align: center;">
                            {{ $statusLabel }}</td>
                        <td rowspan="{{ $po->details->count() }}"
                            style="border: 1px solid #666666; vertical-align: top; border-right: 2px solid #000000;">
                            {{ $po->user->name ?? ($po->nama_petugas ?? '-') }}</td>
                    @endif
                </tr>
            @endforeach
        @else
            <tr>
                <td style="border: 1px solid #D1D5DB;">{{ $po->nomor_faktur ?? $po->nomor }}</td>
                <td style="border: 1px solid #D1D5DB; text-align: center;">
                    {{ \Carbon\Carbon::parse($po->tanggal)->format('d/m/Y') }}</td>
                <td style="border: 1px solid #D1D5DB;">{{ $po->supplier->nama ?? ($po->supplier_nama ?? '-') }}</td>
                <td colspan="7" style="border: 1px solid #D1D5DB; text-align: center; color: #94A3B8;">Tidak ada
                    detail item</td>
                <td style="border: 1px solid #D1D5DB; text-align: right;">
                    {{ $ppnPersen > 0 ? $ppnPersen / 100 : 0 }}</td>
                <td style="border: 1px solid #D1D5DB; text-align: right;">
                    {{ $ppnNominal > 0 ? $ppnNominal : 0 }}</td>
                <td style="border: 1px solid #D1D5DB; text-align: right;">{{ $total }}</td>
                <td style="border: 1px solid #D1D5DB; text-align: right;">{{ $totalBayar }}</td>
                </td>
                <td style="border: 1px solid #D1D5DB; text-align: center;">{{ $statusLabel }}</td>
                <td style="border: 1px solid #D1D5DB;">{{ $po->user->name ?? ($po->nama_petugas ?? '-') }}</td>
            </tr>
        @endif
    @endforeach
    <tr style="background-color: #DBEAFE;">
        <td colspan="12" style="border: 1px solid #000000; text-align: right; font-weight: bold;">TOTAL KESELURUHAN
        </td>
        <td style="border: 1px solid #000000; text-align: right; font-weight: bold;">
            {{ $totalPembelian }}</td>
        <td style="border: 1px solid #000000; text-align: right; font-weight: bold;">
            {{ $totalDibayar }}</td>
        <td colspan="2" style="border: 1px solid #000000;"></td>
    </tr>
    <tr style="background-color: #DBEAFE;">
        <td colspan="12" style="border: 1px solid #000000; text-align: right; font-weight: bold;">SISA PEMBAYARAN</td>
        <td colspan="2" style="border: 1px solid #000000; text-align: right; font-weight: bold;">
            {{ $sisaPembayaran }}</td>
        <td colspan="2" style="border: 1px solid #000000;"></td>
    </tr>
</table>
