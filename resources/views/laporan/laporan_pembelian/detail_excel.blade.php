<table>
    <tr>
        <td colspan="9" style="font-size: 16px; font-weight: bold; text-align: center;">DETAIL PEMBELIAN</td>
    </tr>
    <tr>
        <td colspan="9" style="font-size: 14px; font-weight: bold; text-align: center;">PT. SINAR SURYA</td>
    </tr>
    <tr>
        <td colspan="9" style="font-size: 12px; font-weight: bold; text-align: center;">
            No. Faktur: {{ $pembelian->nomor }}
        </td>
    </tr>
    <tr>
        <td colspan="9" style="font-size: 12px; font-weight: bold; text-align: center;">
            Tanggal: {{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d/m/Y') }}
        </td>
    </tr>
    <tr>
        <td colspan="9" style="font-size: 12px; font-weight: bold; text-align: center;">
            Supplier: {{ $pembelian->supplier->nama }} ({{ $pembelian->supplier->kode }})
        </td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <th
            style="background-color: #4F46E5; color: white; border: 1px solid #000000; text-align: center; font-weight: bold;">
            No</th>
        <th
            style="background-color: #4F46E5; color: white; border: 1px solid #000000; text-align: center; font-weight: bold;">
            Kode</th>
        <th
            style="background-color: #4F46E5; color: white; border: 1px solid #000000; text-align: center; font-weight: bold;">
            Nama Produk</th>
        <th
            style="background-color: #4F46E5; color: white; border: 1px solid #000000; text-align: center; font-weight: bold;">
            Qty</th>
        <th
            style="background-color: #4F46E5; color: white; border: 1px solid #000000; text-align: center; font-weight: bold;">
            Satuan</th>
        <th
            style="background-color: #4F46E5; color: white; border: 1px solid #000000; text-align: center; font-weight: bold;">
            Harga</th>
        <th
            style="background-color: #4F46E5; color: white; border: 1px solid #000000; text-align: center; font-weight: bold;">
            Diskon</th>
        <th
            style="background-color: #4F46E5; color: white; border: 1px solid #000000; text-align: center; font-weight: bold;">
            Subtotal</th>
        <th
            style="background-color: #4F46E5; color: white; border: 1px solid #000000; text-align: center; font-weight: bold;">
            Keterangan</th>
    </tr>
    @forelse($pembelian->details as $index => $item)
        <tr>
            <td style="border: 1px solid #000000; text-align: center;">{{ $index + 1 }}</td>
            <td style="border: 1px solid #000000;">{{ $item->produk->kode }}</td>
            <td style="border: 1px solid #000000;">{{ $item->produk->nama }}</td>
            <td style="border: 1px solid #000000; text-align: center;">{{ $item->quantity }}</td>
            <td style="border: 1px solid #000000; text-align: center;">{{ $item->produk->satuan->nama }}</td>
            <td style="border: 1px solid #000000; text-align: right;">{{ number_format($item->harga, 0, ',', '.') }}
            </td>
            <td style="border: 1px solid #000000; text-align: center;">{{ $item->diskon_persen ?? 0 }}%</td>
            <td style="border: 1px solid #000000; text-align: right;">{{ number_format($item->subtotal, 0, ',', '.') }}
            </td>
            <td style="border: 1px solid #000000;">{{ $item->keterangan }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="9" style="border: 1px solid #000000; text-align: center;">Tidak ada data</td>
        </tr>
    @endforelse
    <tr>
        <td colspan="7" style="border: 1px solid #000000; text-align: right; font-weight: bold;">TOTAL</td>
        <td style="border: 1px solid #000000; text-align: right; font-weight: bold;">
            {{ number_format($pembelian->total, 0, ',', '.') }}</td>
        <td style="border: 1px solid #000000;"></td>
    </tr>
</table>
