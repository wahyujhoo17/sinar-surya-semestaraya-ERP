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
            PPN ( )</th>
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
                          {{ number_format($detail->harga, 2, ',', '.') }}</td>
                    <td style="border: 1px solid #D1D5DB; text-align: right;">
                        {{ number_format($detail->diskon_persen ?? 0, 2, ',', '.') }}%</td>
                    <td style="border: 1px solid #D1D5DB; text-align: right;">
                          {{ number_format($detail->subtotal, 2, ',', '.') }}</td>
                    @if ($index === 0)
                        <td rowspan="{{ $po->details->count() }}"
                            style="border: 1px solid #666666; vertical-align: top; text-align: right;">
                            {{ number_format($ppnPersen, 2, ',', '.') }}%</td>
                        <td rowspan="{{ $po->details->count() }}"
                            style="border: 1px solid #666666; vertical-align: top; text-align: right;">
                              {{ number_format($ppnNominal, 2, ',', '.') }}</td>
                        <td rowspan="{{ $po->details->count() }}"
                            style="border: 1px solid #666666; vertical-align: top; text-align: right;">
                              {{ number_format($total, 2, ',', '.') }}</td>
                        <td rowspan="{{ $po->details->count() }}"
                            style="border: 1px solid #666666; vertical-align: top; text-align: right;">
                              {{ number_format($totalBayar, 2, ',', '.') }}</td>
                        <td rowspan="{{ $po->details->count() }}"
                            style="border: 1px solid #666666; vertical-align: top; text-align: center;">
                            {{ $statusLabel }}</td>
                        <td rowspan="{{ $po->details->count() }}"
                            style="border: 1px solid #666666; vertical-align: top; border-right: 2px solid #000000;">
                            {{ $po->user->name ?? ($po->nama_petugas ?? '-') }}</td>
                    @endif
                </tr>
            @endforeach
            {{-- Tambahkan baris summary jika ada diskon global --}}
            @if ($po->diskon_nominal > 0 || $po->diskon_persen > 0)
                @php
                    $itemSubtotal = $po->details->sum('subtotal');
                @endphp
                <tr style="background-color: #FEF3C7; border-top: 2px solid #F59E0B;">
                    <td colspan="7"
                        style="border: 1px solid #F59E0B; text-align: right; font-weight: 600; color: #92400E; padding-right: 10px;">
                        Subtotal Item:
                    </td>
                    <td colspan="3" style="border: 1px solid #F59E0B; text-align: right; font-weight: 600;">
                          {{ number_format($itemSubtotal, 2, ',', '.') }}
                    </td>
                    <td colspan="6" style="border: 1px solid #F59E0B;"></td>
                </tr>
                <tr style="background-color: #FEF3C7;">
                    <td colspan="7"
                        style="border: 1px solid #F59E0B; text-align: right; font-weight: 600; color: #DC2626; padding-right: 10px;">
                        Diskon Global
                        @if ($po->diskon_persen > 0)
                            ({{ number_format($po->diskon_persen, 2, ',', '.') }}%)
                        @endif:
                    </td>
                    <td colspan="3"
                        style="border: 1px solid #F59E0B; text-align: right; font-weight: 600; color: #DC2626;">
                        -   {{ number_format($po->diskon_nominal ?? 0, 2, ',', '.') }}
                    </td>
                    <td colspan="6" style="border: 1px solid #F59E0B;"></td>
                </tr>
                <tr style="background-color: #FEF3C7; border-bottom: 2px solid #F59E0B;">
                    <td colspan="7"
                        style="border: 1px solid #F59E0B; text-align: right; font-weight: bold; color: #92400E; padding-right: 10px;">
                        Subtotal Setelah Diskon:
                    </td>
                    <td colspan="3" style="border: 1px solid #F59E0B; text-align: right; font-weight: bold;">
                          {{ number_format($itemSubtotal - ($po->diskon_nominal ?? 0), 2, ',', '.') }}
                    </td>
                    <td colspan="6" style="border: 1px solid #F59E0B;"></td>
                </tr>
            @endif
        @else
            <tr>
                <td style="border: 1px solid #D1D5DB;">{{ $po->nomor_faktur ?? $po->nomor }}</td>
                <td style="border: 1px solid #D1D5DB; text-align: center;">
                    {{ \Carbon\Carbon::parse($po->tanggal)->format('d/m/Y') }}</td>
                <td style="border: 1px solid #D1D5DB;">{{ $po->supplier->nama ?? ($po->supplier_nama ?? '-') }}</td>
                <td colspan="7" style="border: 1px solid #D1D5DB; text-align: center; color: #94A3B8;">Tidak ada
                    detail item</td>
                <td style="border: 1px solid #D1D5DB; text-align: right;">
                    {{ number_format($ppnPersen, 2, ',', '.') }}%</td>
                <td style="border: 1px solid #D1D5DB; text-align: right;">
                      {{ number_format($ppnNominal, 2, ',', '.') }}</td>
                <td style="border: 1px solid #D1D5DB; text-align: right;">
                      {{ number_format($total, 2, ',', '.') }}</td>
                <td style="border: 1px solid #D1D5DB; text-align: right;">
                      {{ number_format($totalBayar, 2, ',', '.') }}</td>
                <td style="border: 1px solid #D1D5DB; text-align: center;">{{ $statusLabel }}</td>
                <td style="border: 1px solid #D1D5DB;">{{ $po->user->name ?? ($po->nama_petugas ?? '-') }}</td>
            </tr>
        @endif
    @endforeach
    <tr style="background-color: #DBEAFE;">
        <td colspan="12" style="border: 1px solid #000000; text-align: right; font-weight: bold;">TOTAL KESELURUHAN
        </td>
        <td style="border: 1px solid #000000; text-align: right; font-weight: bold;">
              {{ number_format($totalPembelian, 2, ',', '.') }}</td>
        <td style="border: 1px solid #000000; text-align: right; font-weight: bold;">
              {{ number_format($totalDibayar, 2, ',', '.') }}</td>
        <td colspan="2" style="border: 1px solid #000000;"></td>
    </tr>
    <tr style="background-color: #DBEAFE;">
        <td colspan="12" style="border: 1px solid #000000; text-align: right; font-weight: bold;">SISA PEMBAYARAN</td>
        <td colspan="2" style="border: 1px solid #000000; text-align: right; font-weight: bold;">
              {{ number_format($sisaPembayaran, 2, ',', '.') }}</td>
        <td colspan="2" style="border: 1px solid #000000;"></td>
    </tr>
</table>
