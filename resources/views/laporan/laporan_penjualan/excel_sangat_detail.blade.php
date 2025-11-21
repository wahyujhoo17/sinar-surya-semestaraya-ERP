<table>
    <tr>
        <td colspan="17" style="font-size: 16px; font-weight: bold; text-align: center;">LAPORAN PENJUALAN SANGAT DETAIL
        </td>
    </tr>
    <tr>
        <td colspan="17" style="font-size: 12px; text-align: center;">
            Periode: {{ \Carbon\Carbon::parse($filters['tanggal_awal'] ?? now()->startOfMonth())->format('d M Y') }} s/d
            {{ \Carbon\Carbon::parse($filters['tanggal_akhir'] ?? now())->format('d M Y') }}
        </td>
    </tr>
    <tr>
        <td colspan="17"></td>
    </tr>
    <tr>
        <td colspan="17"></td>
    </tr>
    <tr>
        <td
            style="font-weight: bold; background-color: #F9FAFB; color: #374151; border: 1px solid #D1D5DB; text-align: center;">
            No</td>
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
    @php $no = 1; @endphp
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
                        <td rowspan="{{ $so->details->count() + 5 }}" style="vertical-align: top; text-align: center;">
                            {{ $no++ }}</td>
                        <td rowspan="{{ $so->details->count() + 5 }}" style="vertical-align: top;">{{ $so->nomor }}
                        </td>
                        <td rowspan="{{ $so->details->count() + 5 }}" style="vertical-align: top;">
                            {{ \Carbon\Carbon::parse($so->tanggal)->format('d M Y') }}</td>
                        <td rowspan="{{ $so->details->count() + 5 }}" style="vertical-align: top;">
                            {{ $so->customer->company ?? $so->customer->nama }}</td>
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

            {{-- Payment History Section --}}
            @if ($so->invoices && $so->invoices->count() > 0)
                @php
                    $allPayments = collect();
                    foreach ($so->invoices as $invoice) {
                        if ($invoice->pembayaranPiutang && $invoice->pembayaranPiutang->count() > 0) {
                            $allPayments = $allPayments->merge($invoice->pembayaranPiutang);
                        }
                    }
                @endphp
                @if ($allPayments->count() > 0)
                    <tr style="background-color: #F0F9FF;">
                        <td colspan="17"
                            style="font-weight: bold; color: #0369A1; text-align: center; border-top: 2px solid #0284C7;">
                            RIWAYAT PEMBAYARAN ({{ $allPayments->count() }} transaksi)
                        </td>
                    </tr>
                    <tr style="background-color: #F0F9FF; font-weight: bold;">
                        <td style="text-align: center; border-bottom: 1px solid #BAE6FD;">#</td>
                        <td colspan="2" style="text-align: center; border-bottom: 1px solid #BAE6FD;">Tanggal</td>
                        <td style="text-align: center; border-bottom: 1px solid #BAE6FD;">No Invoice</td>
                        <td style="text-align: center; border-bottom: 1px solid #BAE6FD;">Metode</td>
                        <td colspan="2" style="text-align: center; border-bottom: 1px solid #BAE6FD;">Jumlah Bayar
                        </td>
                        <td colspan="9" style="text-align: center; border-bottom: 1px solid #BAE6FD;">Keterangan</td>
                    </tr>
                    @foreach ($allPayments as $index => $bayar)
                        <tr style="background-color: {{ $index % 2 == 0 ? '#FFFFFF' : '#F0F9FF' }};">
                            <td style="text-align: center; border-bottom: 1px solid #BAE6FD;">{{ $index + 1 }}</td>
                            <td colspan="2" style="border-bottom: 1px solid #BAE6FD;">
                                {{ \Carbon\Carbon::parse($bayar->tanggal)->format('d M Y H:i') }}
                            </td>
                            <td style="border-bottom: 1px solid #BAE6FD;">{{ $bayar->invoice->nomor ?? '-' }}</td>
                            <td style="border-bottom: 1px solid #BAE6FD;">{{ $bayar->metode_pembayaran ?? '-' }}</td>
                            <td colspan="2"
                                style="text-align: right; border-bottom: 1px solid #BAE6FD; font-weight: bold; color: #059669;">
                                Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}
                            </td>
                            <td colspan="9" style="border-bottom: 1px solid #BAE6FD;">{{ $bayar->catatan ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                    <tr style="background-color: #E0F2FE; font-weight: bold;">
                        <td colspan="5" style="text-align: right; border-top: 2px solid #0891B2;">TOTAL PEMBAYARAN:
                        </td>
                        <td colspan="2" style="text-align: right; border-top: 2px solid #0891B2; color: #047857;">
                            Rp {{ number_format($allPayments->sum('jumlah'), 0, ',', '.') }}
                        </td>
                        <td colspan="9" style="border-top: 2px solid #0891B2;"></td>
                    </tr>
                @endif
            @endif

            {{-- Delivery Orders Section --}}
            @if ($so->deliveryOrders && $so->deliveryOrders->count() > 0)
                <tr style="background-color: #FEF3C7;">
                    <td colspan="17"
                        style="font-weight: bold; color: #92400E; text-align: center; border-top: 2px solid #F59E0B;">
                        RIWAYAT PENGIRIMAN ({{ $so->deliveryOrders->count() }} pengiriman)
                    </td>
                </tr>
                <tr style="background-color: #FEF3C7; font-weight: bold;">
                    <td style="text-align: center; border-bottom: 1px solid #FCD34D;">#</td>
                    <td style="text-align: center; border-bottom: 1px solid #FCD34D;">No DO</td>
                    <td colspan="2" style="text-align: center; border-bottom: 1px solid #FCD34D;">Tanggal</td>
                    <td style="text-align: center; border-bottom: 1px solid #FCD34D;">Status</td>
                    <td colspan="2" style="text-align: center; border-bottom: 1px solid #FCD34D;">Qty Dikirim</td>
                    <td colspan="9" style="text-align: center; border-bottom: 1px solid #FCD34D;">Catatan</td>
                </tr>
                @foreach ($so->deliveryOrders as $index => $do)
                    @php
                        $statusLabel = match ($do->status) {
                            'draft' => 'Draft',
                            'proses' => 'Proses',
                            'dikirim' => 'Dikirim',
                            'selesai' => 'Selesai',
                            'batal' => 'Batal',
                            default => ucfirst($do->status),
                        };
                        $totalQty = $do->details ? $do->details->sum('quantity') : 0;
                    @endphp
                    <tr style="background-color: {{ $index % 2 == 0 ? '#FFFFFF' : '#FEF3C7' }};">
                        <td style="text-align: center; border-bottom: 1px solid #FCD34D;">{{ $index + 1 }}</td>
                        <td style="border-bottom: 1px solid #FCD34D;">{{ $do->nomor }}</td>
                        <td colspan="2" style="border-bottom: 1px solid #FCD34D;">
                            {{ \Carbon\Carbon::parse($do->tanggal)->format('d M Y') }}
                        </td>
                        <td style="border-bottom: 1px solid #FCD34D;">{{ $statusLabel }}</td>
                        <td colspan="2" style="text-align: right; border-bottom: 1px solid #FCD34D;">
                            {{ number_format($totalQty, 0, ',', '.') }}
                        </td>
                        <td colspan="9" style="border-bottom: 1px solid #FCD34D;">{{ $do->catatan ?? '-' }}</td>
                    </tr>
                @endforeach
            @endif
        @else
            <tr>
                <td style="text-align: center;">{{ $no++ }}</td>
                <td>{{ $so->nomor }}</td>
                <td>{{ \Carbon\Carbon::parse($so->tanggal)->format('d M Y') }}</td>
                <td>{{ $so->customer->company ?? $so->customer->nama }}</td>
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
        <td colspan="12" style="text-align: right;">TOTAL KESELURUHAN:</td>
        <td style="text-align: right;">{{ number_format($totalPenjualan, 0, ',', '.') }}</td>
        <td colspan="4"></td>
    </tr>
    <tr style="background-color: #DBEAFE;">
        <td colspan="12" style="text-align: right; font-weight: bold;">Uang Muka:</td>
        <td style="text-align: right; font-weight: bold;">{{ number_format($totalUangMuka, 0, ',', '.') }}</td>
        <td colspan="4"></td>
    </tr>
    <tr style="background-color: #DBEAFE;">
        <td colspan="12" style="text-align: right; font-weight: bold;">Total Dibayar:</td>
        <td style="text-align: right; font-weight: bold;">{{ number_format($totalDibayar, 0, ',', '.') }}</td>
        <td colspan="4"></td>
    </tr>
    <tr style="background-color: #DBEAFE;">
        <td colspan="12" style="text-align: right; font-weight: bold;">Sisa Pembayaran:</td>
        <td style="text-align: right; font-weight: bold;">{{ number_format($sisaPembayaran, 0, ',', '.') }}</td>
        <td colspan="4"></td>
    </tr>
</table>
