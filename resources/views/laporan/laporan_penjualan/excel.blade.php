<table>
    <tr>
        <td colspan="18" style="font-size: 16px; font-weight: bold; text-align: center;">LAPORAN PENJUALAN DETAIL</td>
    </tr>
    <tr>
        <td colspan="18" style="font-size: 14px; font-weight: bold; text-align: center;">PT. SINAR SURYA SEMESTARAYA
        </td>
    </tr>
    <tr>
        <td colspan="18" style="font-size: 12px; font-weight: bold; text-align: center;">
            Periode: {{ \Carbon\Carbon::parse($filters['tanggal_awal'])->format('d/m/Y') }} -
            {{ \Carbon\Carbon::parse($filters['tanggal_akhir'])->format('d/m/Y') }}
        </td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <th
            style="background-color: #4F46E5; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            No Faktur</th>
        <th
            style="background-color: #4F46E5; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            Tanggal</th>
        <th
            style="background-color: #4F46E5; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            No Inv</th>
        <th
            style="background-color: #4F46E5; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            No PO</th>
        <th
            style="background-color: #4F46E5; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            Customer</th>
        <th
            style="background-color: #4F46E5; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            Kode Produk</th>
        <th
            style="background-color: #4F46E5; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            Nama Produk</th>
        <th
            style="background-color: #4F46E5; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            Qty</th>
        <th
            style="background-color: #4F46E5; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            Satuan</th>
        <th
            style="background-color: #4F46E5; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            Harga Satuan</th>
        <th
            style="background-color: #4F46E5; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            Disc (%)</th>
        <th
            style="background-color: #4F46E5; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            Subtotal</th>
        <th
            style="background-color: #4F46E5; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            PPN (%)</th>
        <th
            style="background-color: #4F46E5; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            PPN Nominal</th>
        <th
            style="background-color: #4F46E5; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            Total SO</th>
        <th
            style="background-color: #4F46E5; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            Dibayar</th>
        <th
            style="background-color: #4F46E5; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            Status</th>
        <th
            style="background-color: #4F46E5; color: #FFFFFF; border: 2px solid #000000; text-align: center; font-weight: bold; padding: 8px;">
            Petugas</th>
    </tr>

    @foreach ($dataPenjualan as $so)
        @php
            $statusLabel = match ($so->status_pembayaran ?? $so->status) {
                'lunas' => 'Lunas',
                'sebagian' => 'Sebagian',
                'belum_bayar' => 'Belum Bayar',
                'kelebihan_bayar' => 'Kelebihan Bayar',
                default => ucfirst($so->status_pembayaran ?? ($so->status ?? '-')),
            };
            $total = is_numeric($so->total) ? (float) $so->total : 0;
            $totalBayar = is_numeric($so->total_bayar) ? (float) $so->total_bayar : 0;
            $sisa = $total - $totalBayar;

            // Hitung itemSubtotal dari detail produk
            $itemSubtotal = 0;
            $totalPpnNominal = 0;
            if ($so->details && $so->details->count() > 0) {
                $itemSubtotal = $so->details->sum('subtotal');
                // Hitung total PPN dari semua item
                if ($so->ppn > 0) {
                    $ppnPercentage = floatval($so->ppn);
                    foreach ($so->details as $detail) {
                        $subtotalAfterDisc = ($detail->subtotal ?? 0) - ($detail->diskon_nominal ?? 0);
                        $itemPpn = ($ppnPercentage / 100) * $subtotalAfterDisc;
                        $totalPpnNominal += $itemPpn;
                    }
                }
            }
        @endphp
        @if ($so->details && $so->details->count() > 0)
            @foreach ($so->details as $index => $detail)
                <tr style="border-bottom: {{ $loop->last ? '2px solid #000000' : '1px solid #D1D5DB' }};">
                    @if ($index === 0)
                        <td rowspan="{{ $so->details->count() }}"
                            style="border: 1px solid #666666; vertical-align: top; border-left: 2px solid #000000;">
                            {{ $so->nomor_faktur ?? $so->nomor }}</td>
                        <td rowspan="{{ $so->details->count() }}"
                            style="border: 1px solid #666666; vertical-align: top; text-align: center;">
                            {{ \Carbon\Carbon::parse($so->tanggal)->format('d/m/Y') }}</td>
                        <td rowspan="{{ $so->details->count() }}"
                            style="border: 1px solid #666666; vertical-align: top; text-align: center;">
                            {{ $so->nomor_invoice ?: '-' }}</td>
                        <td rowspan="{{ $so->details->count() }}"
                            style="border: 1px solid #666666; vertical-align: top; text-align: center;">
                            {{ $so->nomor_po ?: '-' }}</td>
                        <td rowspan="{{ $so->details->count() }}"
                            style="border: 1px solid #666666; vertical-align: top;">
                            {{ $so->customer_nama ?? '-' }}<br>{{ $so->customer_kode ?? '-' }}</td>
                    @endif
                    <td style="border: 1px solid #D1D5DB;">{{ $detail->produk->kode ?? '-' }}</td>
                    <td style="border: 1px solid #D1D5DB;">{{ $detail->produk->nama ?? '-' }}</td>
                    <td style="border: 1px solid #D1D5DB; text-align: right;">
                        {{ $detail->quantity }}</td>
                    <td style="border: 1px solid #D1D5DB; text-align: center;">
                        {{ $detail->produk->satuan->nama ?? '-' }}</td>
                    <td style="border: 1px solid #D1D5DB; text-align: right;">
                        {{ number_format($detail->harga ?? 0, 2, ',', '.') }}</td>
                    <td style="border: 1px solid #D1D5DB; text-align: right;">
                        {{ $detail->diskon_persen ? number_format($detail->diskon_persen, 2, ',', '.') : 0 }}</td>
                    <td style="border: 1px solid #D1D5DB; text-align: right;">
                        {{ number_format($detail->subtotal ?? 0, 2, ',', '.') }}</td>
                    @php
                        $ppnPercentage = $so->ppn > 0 ? floatval($so->ppn) : 0;
                        $subtotalAfterDisc = ($detail->subtotal ?? 0) - ($detail->diskon_nominal ?? 0);
                        $itemPpnNominal = $so->ppn > 0 ? ($ppnPercentage / 100) * $subtotalAfterDisc : 0;
                    @endphp
                    <td style="border: 1px solid #D1D5DB; text-align: right;">
                        {{ $ppnPercentage > 0 ? number_format($ppnPercentage, 2, ',', '.') : '-' }}</td>
                    <td style="border: 1px solid #D1D5DB; text-align: right;">
                        {{ $itemPpnNominal > 0 ? '   ' . number_format($itemPpnNominal, 2, ',', '.') : '' }}</td>
                    @if ($index === 0)
                        <td rowspan="{{ $so->details->count() }}"
                            style="border: 1px solid #666666; vertical-align: top; text-align: right;">
                            {{ number_format($total, 2, ',', '.') }}</td>
                        <td rowspan="{{ $so->details->count() }}"
                            style="border: 1px solid #666666; vertical-align: top; text-align: right;">
                            {{ number_format($totalBayar, 2, ',', '.') }}</td>
                        <td rowspan="{{ $so->details->count() }}"
                            style="border: 1px solid #666666; vertical-align: top; text-align: center;">
                            {{ $statusLabel }}</td>
                        <td rowspan="{{ $so->details->count() }}"
                            style="border: 1px solid #666666; vertical-align: top; border-right: 2px solid #000000;">
                            {{ $so->nama_petugas ?? '-' }}</td>
                    @endif
                </tr>
            @endforeach
            {{-- Tambahkan baris summary jika ada diskon global --}}
            @if ($so->diskon_nominal > 0 || $so->diskon_persen > 0)
                <tr style="background-color: #FEF3C7; border-top: 2px solid #F59E0B;">
                    <td colspan="9"
                        style="border: 1px solid #F59E0B; text-align: right; font-weight: 600; color: #92400E; padding-right: 10px;">
                        Subtotal Item:
                    </td>
                    <td colspan="3" style="border: 1px solid #F59E0B; text-align: right; font-weight: 600;">
                        {{ number_format($itemSubtotal, 2, ',', '.') }}
                    </td>
                    <td colspan="6" style="border: 1px solid #F59E0B;"></td>
                </tr>
                <tr style="background-color: #FEF3C7;">
                    <td colspan="9"
                        style="border: 1px solid #F59E0B; text-align: right; font-weight: 600; color: #DC2626; padding-right: 10px;">
                        Diskon Global
                        @if ($so->diskon_persen > 0)
                            ({{ number_format($so->diskon_persen, 2, ',', '.') }}%)
                        @endif:
                    </td>
                    <td colspan="3"
                        style="border: 1px solid #F59E0B; text-align: right; font-weight: 600; color: #DC2626;">
                        - {{ number_format($so->diskon_nominal ?? 0, 2, ',', '.') }}
                    </td>
                    <td colspan="6" style="border: 1px solid #F59E0B;"></td>
                </tr>
                <tr style="background-color: #FEF3C7; border-bottom: 2px solid #F59E0B;">
                    <td colspan="9"
                        style="border: 1px solid #F59E0B; text-align: right; font-weight: bold; color: #92400E; padding-right: 10px;">
                        Subtotal Setelah Diskon:
                    </td>
                    <td colspan="3" style="border: 1px solid #F59E0B; text-align: right; font-weight: bold;">
                        {{ number_format($itemSubtotal - ($so->diskon_nominal ?? 0), 2, ',', '.') }}
                    </td>
                    <td colspan="6" style="border: 1px solid #F59E0B;"></td>
                </tr>
            @endif
        @else
            <tr>
                <td style="border: 1px solid #D1D5DB;">{{ $so->nomor_faktur ?? $so->nomor }}</td>
                <td style="border: 1px solid #D1D5DB; text-align: center;">
                    {{ \Carbon\Carbon::parse($so->tanggal)->format('d/m/Y') }}</td>
                <td style="border: 1px solid #D1D5DB; text-align: center;">
                    {{ $so->nomor_invoice ?: '-' }}</td>
                <td style="border: 1px solid #D1D5DB; text-align: center;">
                    {{ $so->nomor_po ?: '-' }}</td>
                <td style="border: 1px solid #D1D5DB;">
                    {{ $so->customer_nama ?? '-' }}<br>{{ $so->customer_kode ?? '-' }}</td>
                <td colspan="9" style="border: 1px solid #D1D5DB; text-align: center; color: #94A3B8;">Tidak ada
                    detail item</td>
                <td style="border: 1px solid #D1D5DB; text-align: right;">
                    {{ number_format($total, 2, ',', '.') }}</td>
                <td style="border: 1px solid #D1D5DB; text-align: right;">
                    {{ number_format($totalBayar, 2, ',', '.') }}</td>
                <td style="border: 1px solid #D1D5DB; text-align: center;">{{ $statusLabel }}</td>
                <td style="border: 1px solid #D1D5DB;">{{ $so->nama_petugas ?? '-' }}</td>
            </tr>
        @endif
    @endforeach
    <tr style="background-color: #DBEAFE;">
        <td colspan="14" style="border: 1px solid #000000; text-align: right; font-weight: bold;">TOTAL KESELURUHAN
        </td>
        <td style="border: 1px solid #000000; text-align: right; font-weight: bold;">
            {{ number_format($totalPenjualan, 2, ',', '.') }}</td>
        <td colspan="3" style="border: 1px solid #000000;"></td>
    </tr>
    <tr style="background-color: #DBEAFE;">
        <td colspan="14" style="border: 1px solid #000000; text-align: right; font-weight: bold;">SISA PEMBAYARAN</td>
        <td style="border: 1px solid #000000; text-align: right; font-weight: bold;">
            {{ number_format($sisaPembayaran, 2, ',', '.') }}</td>
        <td colspan="3" style="border: 1px solid #000000;"></td>
    </tr>
</table>
