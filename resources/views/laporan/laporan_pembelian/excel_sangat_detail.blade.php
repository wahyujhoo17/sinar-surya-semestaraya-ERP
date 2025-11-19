<table>
    <tr>
        <td colspan="15" style="font-size: 16px; font-weight: bold; text-align: center;">LAPORAN PEMBELIAN SANGAT DETAIL
        </td>
    </tr>
    <tr>
        <td colspan="15" style="font-size: 12px; text-align: center;">
            Periode: {{ \Carbon\Carbon::parse($filters['tanggal_awal'] ?? now()->startOfMonth())->format('d M Y') }} s/d
            {{ \Carbon\Carbon::parse($filters['tanggal_akhir'] ?? now())->format('d M Y') }}
        </td>
    </tr>
    <tr>
        <td colspan="15"></td>
    </tr>
    <tr>
        <td colspan="15"></td>
    </tr>
    <tr>
        <td
            style="font-weight: bold; background-color: #F9FAFB; color: #374151; border: 1px solid #D1D5DB; text-align: center;">
            No Faktur</td>
        <td
            style="font-weight: bold; background-color: #F9FAFB; color: #374151; border: 1px solid #D1D5DB; text-align: center;">
            Tanggal</td>
        <td
            style="font-weight: bold; background-color: #F9FAFB; color: #374151; border: 1px solid #D1D5DB; text-align: center;">
            Supplier</td>
        <td
            style="font-weight: bold; background-color: #F9FAFB; color: #374151; border: 1px solid #D1D5DB; text-align: center;">
            Kode Produk</td>
        <td
            style="font-weight: bold; background-color: #F9FAFB; color: #374151; border: 1px solid #D1D5DB; text-align: center;">
            Nama Produk</td>
        <td
            style="font-weight: bold; background-color: #F9FAFB; color: #374151; border: 1px solid #D1D5DB; text-align: center;">
            Qty</td>
        <td
            style="font-weight: bold; background-color: #F9FAFB; color: #374151; border: 1px solid #D1D5DB; text-align: center;">
            Satuan</td>
        <td
            style="font-weight: bold; background-color: #F9FAFB; color: #374151; border: 1px solid #D1D5DB; text-align: center;">
            Harga Satuan</td>
        <td
            style="font-weight: bold; background-color: #F9FAFB; color: #374151; border: 1px solid #D1D5DB; text-align: center;">
            Disc (%)</td>
        <td
            style="font-weight: bold; background-color: #F9FAFB; color: #374151; border: 1px solid #D1D5DB; text-align: center;">
            Subtotal</td>
        <td
            style="font-weight: bold; background-color: #F9FAFB; color: #374151; border: 1px solid #D1D5DB; text-align: center;">
            Total Item</td>
        <td
            style="font-weight: bold; background-color: #F9FAFB; color: #374151; border: 1px solid #D1D5DB; text-align: center;">
            Total PO</td>
        <td
            style="font-weight: bold; background-color: #F9FAFB; color: #374151; border: 1px solid #D1D5DB; text-align: center;">
            Dibayar</td>
        <td
            style="font-weight: bold; background-color: #F9FAFB; color: #374151; border: 1px solid #D1D5DB; text-align: center;">
            Status</td>
        <td
            style="font-weight: bold; background-color: #F9FAFB; color: #374151; border: 1px solid #D1D5DB; text-align: center;">
            Petugas</td>
    </tr>
    @foreach ($dataPembelian as $po)
        @php
            $statusLabel = match ($po->status_pembayaran) {
                'lunas' => 'Lunas',
                'sebagian' => 'Dibayar Sebagian',
                'belum_bayar' => 'Belum Dibayar',
                default => ucfirst($po->status_pembayaran),
            };
            $itemSubtotal = $po->details->sum('total');
        @endphp
        @if ($po->details->count() > 0)
            @foreach ($po->details as $index => $detail)
                <tr>
                    @if ($index === 0)
                        <td rowspan="{{ $po->details->count() + 5 }}" style="vertical-align: top;">{{ $po->nomor }}
                        </td>
                        <td rowspan="{{ $po->details->count() + 5 }}" style="vertical-align: top;">
                            {{ \Carbon\Carbon::parse($po->tanggal)->format('d M Y') }}</td>
                        <td rowspan="{{ $po->details->count() + 5 }}" style="vertical-align: top;">
                            {{ $po->supplier->nama ?? '-' }}</td>
                    @endif
                    <td>{{ $detail->produk->kode ?? '-' }}</td>
                    <td>{{ $detail->produk->nama ?? '-' }}</td>
                    <td style="text-align: center;">{{ number_format($detail->quantity, 0, ',', '.') }}</td>
                    <td style="text-align: center;">{{ $detail->produk->satuan->nama ?? '-' }}</td>
                    <td style="text-align: right;">{{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td style="text-align: right;">{{ number_format($detail->discount ?? 0, 2, ',', '.') }}</td>
                    <td style="text-align: right;">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    <td style="text-align: right;">{{ number_format($detail->total, 0, ',', '.') }}</td>
                    @if ($index === 0)
                        <td rowspan="{{ $po->details->count() + 5 }}" style="vertical-align: top; text-align: right;">
                            {{ number_format($po->total, 0, ',', '.') }}</td>
                        <td rowspan="{{ $po->details->count() + 5 }}" style="vertical-align: top; text-align: right;">
                            {{ number_format($po->total_bayar, 0, ',', '.') }}</td>
                        <td rowspan="{{ $po->details->count() + 5 }}" style="vertical-align: top; text-align: center;">
                            {{ $statusLabel }}</td>
                        <td rowspan="{{ $po->details->count() + 5 }}" style="vertical-align: top;">
                            {{ $po->user->name ?? '-' }}</td>
                    @endif
                </tr>
            @endforeach
            {{-- Subtotal, PPN, Ongkir --}}
            <tr style="background-color: #F9FAFB;">
                <td colspan="7" style="text-align: right; font-weight: 600;">Subtotal Item:</td>
                <td style="text-align: right; font-weight: 600;">{{ number_format($itemSubtotal, 0, ',', '.') }}</td>
            </tr>
            @if ($po->ongkos_kirim > 0)
                <tr style="background-color: #F9FAFB;">
                    <td colspan="7" style="text-align: right; font-weight: 600;">Ongkos Kirim:</td>
                    <td style="text-align: right; font-weight: 600;">
                        {{ number_format($po->ongkos_kirim, 0, ',', '.') }}</td>
                </tr>
            @else
                <tr style="background-color: #F9FAFB;">
                    <td colspan="7" style="text-align: right; font-weight: 600;">Ongkos Kirim:</td>
                    <td style="text-align: right; font-weight: 600;">-</td>
                </tr>
            @endif
            @if ($po->ppn > 0)
                @php
                    $ppnPercentage = floatval($po->ppn);
                    $ppnNominal = ($ppnPercentage / 100) * $po->subtotal;
                @endphp
                <tr style="background-color: #F9FAFB;">
                    <td colspan="7" style="text-align: right; font-weight: 600;">PPN
                        ({{ number_format($ppnPercentage, 0) }}%):</td>
                    <td style="text-align: right; font-weight: 600;">{{ number_format($ppnNominal, 0, ',', '.') }}</td>
                </tr>
            @else
                <tr style="background-color: #F9FAFB;">
                    <td colspan="7" style="text-align: right; font-weight: 600;">PPN:</td>
                    <td style="text-align: right; font-weight: 600;">-</td>
                </tr>
            @endif
            <tr style="background-color: #E0E7FF; font-weight: bold;">
                <td colspan="7" style="text-align: right;">TOTAL PEMBELIAN:</td>
                <td style="text-align: right;">{{ number_format($po->total, 0, ',', '.') }}</td>
            </tr>
            <tr style="background-color: #ECFDF5; font-weight: bold;">
                <td colspan="7" style="text-align: right;">Sisa:</td>
                <td style="text-align: right;">{{ number_format($po->total - $po->total_bayar, 0, ',', '.') }}</td>
            </tr>
        @else
            <tr>
                <td>{{ $po->nomor }}</td>
                <td>{{ \Carbon\Carbon::parse($po->tanggal)->format('d M Y') }}</td>
                <td>{{ $po->supplier->nama ?? '-' }}</td>
                <td colspan="8" style="text-align: center; color: #94A3B8;">Tidak ada detail item</td>
                <td style="text-align: right;">{{ number_format($po->total, 0, ',', '.') }}</td>
                <td style="text-align: right;">{{ number_format($po->total_bayar, 0, ',', '.') }}</td>
                <td style="text-align: center;">{{ $statusLabel }}</td>
                <td>{{ $po->user->name ?? '-' }}</td>
            </tr>
        @endif
    @endforeach
    {{-- Grand Total --}}
    <tr style="background-color: #DBEAFE; font-weight: bold;">
        <td colspan="11" style="text-align: right;">TOTAL KESELURUHAN:</td>
        <td style="text-align: right;">{{ number_format($totalPembelian, 0, ',', '.') }}</td>
        <td colspan="3"></td>
    </tr>
    <tr style="background-color: #DBEAFE;">
        <td colspan="11" style="text-align: right; font-weight: bold;">Total Dibayar:</td>
        <td style="text-align: right; font-weight: bold;">{{ number_format($totalDibayar, 0, ',', '.') }}</td>
        <td colspan="3"></td>
    </tr>
    <tr style="background-color: #DBEAFE;">
        <td colspan="11" style="text-align: right; font-weight: bold;">Sisa Pembayaran:</td>
        <td style="text-align: right; font-weight: bold;">{{ number_format($sisaPembayaran, 0, ',', '.') }}</td>
        <td colspan="3"></td>
    </tr>
</table>
