<table>
    <tr>
        <td colspan="16" style="font-size: 16px; font-weight: bold; text-align: center;">LAPORAN PEMBELIAN SANGAT DETAIL
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
            style="font-weight: bold; background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; padding: 8px;">
            No Faktur</td>
        <td
            style="font-weight: bold; background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; padding: 8px;">
            Tanggal</td>
        <td
            style="font-weight: bold; background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; padding: 8px;">
            Supplier</td>
        <td
            style="font-weight: bold; background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; padding: 8px;">
            Kode Produk</td>
        <td
            style="font-weight: bold; background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; padding: 8px;">
            Nama Produk</td>
        <td
            style="font-weight: bold; background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; padding: 8px;">
            Qty</td>
        <td
            style="font-weight: bold; background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; padding: 8px;">
            Satuan</td>
        <td
            style="font-weight: bold; background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; padding: 8px;">
            Harga Satuan</td>
        <td
            style="font-weight: bold; background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; padding: 8px;">
            Disc (%)</td>
        <td
            style="font-weight: bold; background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; padding: 8px;">
            Subtotal</td>
        <td
            style="font-weight: bold; background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; padding: 8px;">
            PPN (%)</td>
        <td
            style="font-weight: bold; background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; padding: 8px;">
            PPN (Rp)</td>
        <td
            style="font-weight: bold; background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; padding: 8px;">
            Total PO</td>
        <td
            style="font-weight: bold; background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; padding: 8px;">
            Dibayar</td>
        <td
            style="font-weight: bold; background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; padding: 8px;">
            Status</td>
        <td
            style="font-weight: bold; background-color: #1F2937; color: #FFFFFF; border: 2px solid #000000; text-align: center; padding: 8px;">
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

            // Hitung PPN
            $ppnPersen = floatval($po->ppn ?? 0);
            $ppnNominal = ($ppnPersen / 100) * floatval($po->subtotal ?? 0);
        @endphp
        @if ($po->details->count() > 0)
            {{-- Detail Items --}}
            @foreach ($po->details as $index => $detail)
                <tr>
                    @if ($index === 0)
                        <td style="border: 1px solid #666666; vertical-align: top; border-left: 2px solid #000000;">
                            {{ $po->nomor }}</td>
                        <td style="border: 1px solid #666666; vertical-align: top;">
                            {{ \Carbon\Carbon::parse($po->tanggal)->format('d M Y') }}</td>
                        <td style="border: 1px solid #666666; vertical-align: top;">
                            {{ $po->supplier->nama ?? '-' }}</td>
                    @else
                        <td style="border: 1px solid #D1D5DB;"></td>
                        <td style="border: 1px solid #D1D5DB;"></td>
                        <td style="border: 1px solid #D1D5DB;"></td>
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
                        {{ $detail->diskon_persen ?? 0 }}</td>
                    <td style="border: 1px solid #D1D5DB; text-align: right;">
                        {{ $detail->subtotal }}</td>
                    @if ($index === 0)
                        <td style="border: 1px solid #666666; vertical-align: top; text-align: right;">
                            {{ $ppnPersen > 0 ? $ppnPersen / 100 : 0 }}</td>
                        <td style="border: 1px solid #666666; vertical-align: top; text-align: right;">
                            {{ $ppnNominal > 0 ? $ppnNominal : 0 }}</td>
                        <td style="border: 1px solid #666666; vertical-align: top; text-align: right;">
                            {{ $po->total }}</td>
                        <td style="border: 1px solid #666666; vertical-align: top; text-align: right;">
                            {{ $po->total_bayar }}</td>
                        <td style="border: 1px solid #666666; vertical-align: top; text-align: center;">
                            {{ $statusLabel }}</td>
                        <td style="border: 1px solid #666666; vertical-align: top; border-right: 2px solid #000000;">
                            {{ $po->user->name ?? '-' }}</td>
                    @else
                        <td style="border: 1px solid #D1D5DB;"></td>
                        <td style="border: 1px solid #D1D5DB;"></td>
                        <td style="border: 1px solid #D1D5DB;"></td>
                        <td style="border: 1px solid #D1D5DB;"></td>
                        <td style="border: 1px solid #D1D5DB;"></td>
                        <td style="border: 1px solid #D1D5DB;"></td>
                    @endif
                </tr>
            @endforeach

            {{-- Subtotal Item --}}
            <tr style="background-color: #F9FAFB; border-top: 2px solid #666666;">
                <td colspan="3" style="border: 1px solid #D1D5DB; border-left: 2px solid #000000;"></td>
                <td colspan="6" style="border: 1px solid #D1D5DB; text-align: right; font-weight: 600;">Subtotal
                    Item:</td>
                <td style="border: 1px solid #D1D5DB; text-align: right; font-weight: 600;">
                    {{ $itemSubtotal }}</td>
                <td colspan="6" style="border: 1px solid #D1D5DB; border-right: 2px solid #000000;"></td>
            </tr>

            {{-- Ongkos Kirim --}}
            <tr style="background-color: #F9FAFB;">
                <td colspan="3" style="border: 1px solid #D1D5DB; border-left: 2px solid #000000;"></td>
                <td colspan="6" style="border: 1px solid #D1D5DB; text-align: right; font-weight: 600;">Ongkos Kirim:
                </td>
                <td style="border: 1px solid #D1D5DB; text-align: right; font-weight: 600;">
                    {{ $po->ongkos_kirim > 0 ? $po->ongkos_kirim : 0 }}</td>
                <td colspan="6" style="border: 1px solid #D1D5DB; border-right: 2px solid #000000;"></td>
            </tr>

            {{-- PPN --}}
            <tr style="background-color: #F9FAFB;">
                <td colspan="3" style="border: 1px solid #D1D5DB; border-left: 2px solid #000000;"></td>
                <td colspan="6" style="border: 1px solid #D1D5DB; text-align: right; font-weight: 600;">
                    PPN {{ $ppnPersen > 0 ? '(' . number_format($ppnPersen, 0) . '%)' : '' }}:</td>
                <td style="border: 1px solid #D1D5DB; text-align: right; font-weight: 600;">
                    {{ $ppnNominal > 0 ? $ppnNominal : 0 }}</td>
                <td colspan="6" style="border: 1px solid #D1D5DB; border-right: 2px solid #000000;"></td>
            </tr>

            {{-- Total Pembelian --}}
            <tr style="background-color: #E0E7FF; font-weight: bold; border-bottom: 2px solid #000000;">
                <td colspan="3" style="border: 1px solid #D1D5DB; border-left: 2px solid #000000;"></td>
                <td colspan="6" style="border: 1px solid #D1D5DB; text-align: right;">TOTAL PEMBELIAN:</td>
                <td style="border: 1px solid #D1D5DB; text-align: right;">{{ $po->total }}
                </td>
                <td colspan="6" style="border: 1px solid #D1D5DB; border-right: 2px solid #000000;"></td>
            </tr>

            {{-- History Penerimaan Barang --}}
            @if ($po->penerimaan && $po->penerimaan->count() > 0)
                <tr style="background-color: #FEF3C7; border-top: 2px solid #000000;">
                    <td colspan="3" style="border: 1px solid #D1D5DB; border-left: 2px solid #000000;"></td>
                    <td colspan="10"
                        style="border: 2px solid #F59E0B; font-weight: bold; padding: 8px; background-color: #FCD34D;">
                        HISTORY PENERIMAAN BARANG
                    </td>
                    <td colspan="3" style="border: 1px solid #D1D5DB; border-right: 2px solid #000000;"></td>
                </tr>
                @foreach ($po->penerimaan as $penerimaan)
                    <tr style="background-color: #FFFBEB;">
                        <td colspan="3" style="border: 1px solid #D1D5DB; border-left: 2px solid #000000;"></td>
                        <td colspan="2" style="border: 1px solid #D1D5DB; padding-left: 8px;">
                            {{ $penerimaan->nomor }}
                        </td>
                        <td colspan="3" style="border: 1px solid #D1D5DB;">
                            {{ \Carbon\Carbon::parse($penerimaan->tanggal)->format('d M Y') }}
                        </td>
                        <td colspan="3" style="border: 1px solid #D1D5DB;">
                            SJ: {{ $penerimaan->nomor_surat_jalan ?? '-' }}
                        </td>
                        <td colspan="2" style="border: 1px solid #D1D5DB;">
                            {{ $penerimaan->gudang->nama ?? '-' }}
                        </td>
                        <td colspan="3" style="border: 1px solid #D1D5DB; border-right: 2px solid #000000;"></td>
                    </tr>
                @endforeach
            @endif

            {{-- History Pembayaran --}}
            @if ($po->pembayaran && $po->pembayaran->count() > 0)
                <tr style="background-color: #D1FAE5; border-top: 2px solid #000000;">
                    <td colspan="3" style="border: 1px solid #D1D5DB; border-left: 2px solid #000000;"></td>
                    <td colspan="10"
                        style="border: 2px solid #10B981; font-weight: bold; padding: 8px; background-color: #6EE7B7;">
                        HISTORY PEMBAYARAN
                    </td>
                    <td colspan="3" style="border: 1px solid #D1D5DB; border-right: 2px solid #000000;"></td>
                </tr>
                @foreach ($po->pembayaran as $bayar)
                    <tr
                        style="background-color: #ECFDF5; border-bottom: {{ $loop->last ? '2px solid #000000' : '1px solid #D1D5DB' }};">
                        <td colspan="3" style="border: 1px solid #D1D5DB; border-left: 2px solid #000000;"></td>
                        <td colspan="2" style="border: 1px solid #D1D5DB; padding-left: 8px;">
                            {{ $bayar->nomor }}
                        </td>
                        <td colspan="3" style="border: 1px solid #D1D5DB;">
                            {{ \Carbon\Carbon::parse($bayar->tanggal)->format('d M Y') }}
                        </td>
                        <td colspan="3" style="border: 1px solid #D1D5DB; text-align: right;">
                            {{ $bayar->jumlah }}
                        </td>
                        <td colspan="2" style="border: 1px solid #D1D5DB;">
                            {{ ucfirst($bayar->metode_pembayaran ?? '-') }}
                        </td>
                        <td colspan="3" style="border: 1px solid #D1D5DB; border-right: 2px solid #000000;"></td>
                    </tr>
                @endforeach
            @endif
        @else
            <tr>
                <td style="border: 1px solid #D1D5DB;">{{ $po->nomor }}</td>
                <td style="border: 1px solid #D1D5DB;">{{ \Carbon\Carbon::parse($po->tanggal)->format('d M Y') }}</td>
                <td style="border: 1px solid #D1D5DB;">{{ $po->supplier->nama ?? '-' }}</td>
                <td colspan="7" style="border: 1px solid #D1D5DB; text-align: center; color: #94A3B8;">Tidak ada
                    detail item</td>
                <td style="border: 1px solid #D1D5DB; text-align: right;">
                    {{ $ppnPersen > 0 ? $ppnPersen / 100 : 0 }}</td>
                <td style="border: 1px solid #D1D5DB; text-align: right;">
                    {{ $ppnNominal > 0 ? $ppnNominal : 0 }}</td>
                <td style="border: 1px solid #D1D5DB; text-align: right;">{{ $po->total }}
                </td>
                <td style="border: 1px solid #D1D5DB; text-align: right;">
                    {{ $po->total_bayar }}</td>
                <td style="border: 1px solid #D1D5DB; text-align: center;">{{ $statusLabel }}</td>
                <td style="border: 1px solid #D1D5DB;">{{ $po->user->name ?? '-' }}</td>
            </tr>
        @endif
    @endforeach
    {{-- Grand Total --}}
    <tr style="background-color: #DBEAFE; font-weight: bold;">
        <td colspan="12" style="border: 1px solid #000000; text-align: right;">TOTAL KESELURUHAN:</td>
        <td style="border: 1px solid #000000; text-align: right;">{{ $totalPembelian }}
        </td>
        <td colspan="3" style="border: 1px solid #000000;"></td>
    </tr>
    <tr style="background-color: #DBEAFE;">
        <td colspan="12" style="border: 1px solid #000000; text-align: right; font-weight: bold;">Total Dibayar:
        </td>
        <td style="border: 1px solid #000000; text-align: right; font-weight: bold;">
            {{ $totalDibayar }}</td>
        <td colspan="3" style="border: 1px solid #000000;"></td>
    </tr>
    <tr style="background-color: #DBEAFE;">
        <td colspan="12" style="border: 1px solid #000000; text-align: right; font-weight: bold;">Sisa Pembayaran:
        </td>
        <td style="border: 1px solid #000000; text-align: right; font-weight: bold;">
            {{ $sisaPembayaran }}</td>
        <td colspan="3" style="border: 1px solid #000000;"></td>
    </tr>
</table>
