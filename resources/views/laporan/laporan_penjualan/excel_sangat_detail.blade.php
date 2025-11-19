<table>
    <tr>
        <td colspan="16" style="font-size: 16px; font-weight: bold; text-align: center;">LAPORAN PENJUALAN SANGAT DETAIL
        </td>
    </tr>
    <tr>
        <td colspan="16" style="font-size: 12px; text-align: center;">
            Periode: {{ \Carbon\Carbon::parse($filters['tanggal_awal'] ?? now()->startOfMonth())->format('d M Y') }} s/d
            {{ \Carbon\Carbon::parse($filters['tanggal_akhir'] ?? now())->format('d M Y') }}
        </td>
    </tr>
    <tr>
        <td colspan="16"></td>
    </tr>
    <tr>
        <td colspan="16"></td>
    </tr>
    <tr>
        <td
            style="font-weight: bold; background-color: #F9FAFB; color: #374151; border: 1px solid #D1D5DB; text-align: center;">
            No SO</td>
        <td
            style="font-weight: bold; background-color: #F9FAFB; color: #374151; border: 1px solid #D1D5DB; text-align: center;">
            Tanggal</td>
        <td
            style="font-weight: bold; background-color: #F9FAFB; color: #374151; border: 1px solid #D1D5DB; text-align: center;">
            Customer</td>
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
            Total SO</td>
        <td
            style="font-weight: bold; background-color: #F9FAFB; color: #374151; border: 1px solid #D1D5DB; text-align: center;">
            Uang Muka</td>
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
    @foreach ($dataPenjualan as $so)
        @php
            $statusLabel = match ($so->status_pembayaran) {
                'lunas' => 'Lunas',
                'sebagian' => 'Dibayar Sebagian',
                'belum_bayar' => 'Belum Dibayar',
                default => ucfirst($so->status_pembayaran),
            };
            $itemSubtotal = $so->details->sum(function ($detail) {
                return ($detail->subtotal ?? 0) - ($detail->diskon_nominal ?? 0);
            });
        @endphp
        @if ($so->details->count() > 0)
            @foreach ($so->details as $index => $detail)
                <tr>
                    @if ($index === 0)
                        <td rowspan="{{ $so->details->count() + 5 }}" style="vertical-align: top;">{{ $so->nomor }}
                        </td>
                        <td rowspan="{{ $so->details->count() + 5 }}" style="vertical-align: top;">
                            {{ \Carbon\Carbon::parse($so->tanggal)->format('d M Y') }}</td>
                        <td rowspan="{{ $so->details->count() + 5 }}" style="vertical-align: top;">
                            {{ $so->customer->nama ?? '-' }}</td>
                    @endif
                    <td>{{ $detail->produk->kode ?? '-' }}</td>
                    <td>{{ $detail->produk->nama ?? '-' }}</td>
                    <td style="text-align: center;">{{ number_format($detail->quantity, 0, ',', '.') }}</td>
                    <td style="text-align: center;">{{ $detail->produk->satuan->nama ?? '-' }}</td>
                    <td style="text-align: right;">{{ number_format($detail->harga ?? 0, 0, ',', '.') }}</td>
                    <td style="text-align: right;">{{ number_format($detail->diskon_persen ?? 0, 2, ',', '.') }}</td>
                    <td style="text-align: right;">{{ number_format($detail->subtotal ?? 0, 0, ',', '.') }}</td>
                    <td style="text-align: right;">
                        {{ number_format(($detail->subtotal ?? 0) - ($detail->diskon_nominal ?? 0), 0, ',', '.') }}
                    </td>
                    @if ($index === 0)
                        <td rowspan="{{ $so->details->count() + 5 }}" style="vertical-align: top; text-align: right;">
                            {{ number_format($so->total, 0, ',', '.') }}</td>
                        <td rowspan="{{ $so->details->count() + 5 }}" style="vertical-align: top; text-align: right;">
                            {{ number_format($so->total_uang_muka, 0, ',', '.') }}</td>
                        <td rowspan="{{ $so->details->count() + 5 }}" style="vertical-align: top; text-align: right;">
                            {{ number_format($so->total_bayar, 0, ',', '.') }}</td>
                        <td rowspan="{{ $so->details->count() + 5 }}" style="vertical-align: top; text-align: center;">
                            {{ $statusLabel }}</td>
                        <td rowspan="{{ $so->details->count() + 5 }}" style="vertical-align: top;">
                            {{ $so->user->name ?? '-' }}</td>
                    @endif
                </tr>
            @endforeach
            {{-- Subtotal, PPN, Ongkir --}}
            <tr style="background-color: #F9FAFB;">
                <td colspan="7" style="text-align: right; font-weight: 600;">Subtotal Item:</td>
                <td style="text-align: right; font-weight: 600;">{{ number_format($itemSubtotal, 0, ',', '.') }}</td>
            </tr>
            @if ($so->ongkos_kirim > 0)
                <tr style="background-color: #F9FAFB;">
                    <td colspan="7" style="text-align: right; font-weight: 600;">Ongkos Kirim:</td>
                    <td style="text-align: right; font-weight: 600;">
                        {{ number_format($so->ongkos_kirim, 0, ',', '.') }}</td>
                </tr>
            @else
                <tr style="background-color: #F9FAFB;">
                    <td colspan="7" style="text-align: right; font-weight: 600;">Ongkos Kirim:</td>
                    <td style="text-align: right; font-weight: 600;">-</td>
                </tr>
            @endif
            @if ($so->ppn > 0)
                @php
                    $ppnPercentage = floatval($so->ppn);
                    $ppnNominal = ($ppnPercentage / 100) * $so->subtotal;
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
                <td colspan="7" style="text-align: right;">TOTAL PENJUALAN:</td>
                <td style="text-align: right;">{{ number_format($so->total, 0, ',', '.') }}</td>
            </tr>
            <tr style="background-color: #ECFDF5; font-weight: bold;">
                <td colspan="7" style="text-align: right;">Sisa:</td>
                <td style="text-align: right;">{{ number_format($so->total - $so->total_bayar, 0, ',', '.') }}</td>
            </tr>
        @else
            <tr>
                <td>{{ $so->nomor }}</td>
                <td>{{ \Carbon\Carbon::parse($so->tanggal)->format('d M Y') }}</td>
                <td>{{ $so->customer->nama ?? '-' }}</td>
                <td colspan="8" style="text-align: center; color: #94A3B8;">Tidak ada detail item</td>
                <td style="text-align: right;">{{ number_format($so->total, 0, ',', '.') }}</td>
                <td style="text-align: right;">{{ number_format($so->total_uang_muka, 0, ',', '.') }}</td>
                <td style="text-align: right;">{{ number_format($so->total_bayar, 0, ',', '.') }}</td>
                <td style="text-align: center;">{{ $statusLabel }}</td>
                <td>{{ $so->user->name ?? '-' }}</td>
            </tr>
        @endif
    @endforeach
    {{-- Grand Total --}}
    <tr style="background-color: #DBEAFE; font-weight: bold;">
        <td colspan="11" style="text-align: right;">TOTAL KESELURUHAN:</td>
        <td style="text-align: right;">{{ number_format($totalPenjualan, 0, ',', '.') }}</td>
        <td colspan="4"></td>
    </tr>
    <tr style="background-color: #DBEAFE;">
        <td colspan="11" style="text-align: right; font-weight: bold;">Uang Muka:</td>
        <td style="text-align: right; font-weight: bold;">{{ number_format($totalUangMuka, 0, ',', '.') }}</td>
        <td colspan="4"></td>
    </tr>
    <tr style="background-color: #DBEAFE;">
        <td colspan="11" style="text-align: right; font-weight: bold;">Total Dibayar:</td>
        <td style="text-align: right; font-weight: bold;">{{ number_format($totalDibayar, 0, ',', '.') }}</td>
        <td colspan="4"></td>
    </tr>
    <tr style="background-color: #DBEAFE;">
        <td colspan="11" style="text-align: right; font-weight: bold;">Sisa Pembayaran:</td>
        <td style="text-align: right; font-weight: bold;">{{ number_format($sisaPembayaran, 0, ',', '.') }}</td>
        <td colspan="4"></td>
    </tr>
</table>
