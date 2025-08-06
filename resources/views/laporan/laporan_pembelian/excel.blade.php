<table>
    <tr>
        <td colspan="10" style="font-size: 16px; font-weight: bold; text-align: center;">LAPORAN PEMBELIAN</td>
    </tr>
    <tr>
        <td colspan="10" style="font-size: 14px; font-weight: bold; text-align: center;">PT. SINAR SURYA SSEMESTARAYA
        </td>
    </tr>
    <tr>
        <td colspan="10" style="font-size: 12px; font-weight: bold; text-align: center;">
            Periode: {{ \Carbon\Carbon::parse($filters['tanggal_awal'])->format('d/m/Y') }} -
            {{ \Carbon\Carbon::parse($filters['tanggal_akhir'])->format('d/m/Y') }}
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
            Nomor Faktur</th>
        <th
            style="background-color: #4F46E5; color: white; border: 1px solid #000000; text-align: center; font-weight: bold;">
            Tanggal</th>
        <th
            style="background-color: #4F46E5; color: white; border: 1px solid #000000; text-align: center; font-weight: bold;">
            Supplier</th>
        <th
            style="background-color: #4F46E5; color: white; border: 1px solid #000000; text-align: center; font-weight: bold;">
            Status</th>
        <th
            style="background-color: #4F46E5; color: white; border: 1px solid #000000; text-align: center; font-weight: bold;">
            Total</th>
        <th
            style="background-color: #4F46E5; color: white; border: 1px solid #000000; text-align: center; font-weight: bold;">
            Total Bayar</th>
        <th
            style="background-color: #4F46E5; color: white; border: 1px solid #000000; text-align: center; font-weight: bold;">
            Sisa</th>
        <th
            style="background-color: #4F46E5; color: white; border: 1px solid #000000; text-align: center; font-weight: bold;">
            Petugas</th>
        <th
            style="background-color: #4F46E5; color: white; border: 1px solid #000000; text-align: center; font-weight: bold;">
            Keterangan</th>
    </tr>

    @foreach ($dataPembelian as $index => $item)
        @php
            // Ensure numeric values are properly handled
            $total = is_numeric($item->total) ? (float) $item->total : 0;
            $totalBayar = is_numeric($item->total_bayar) ? (float) $item->total_bayar : 0;
            $sisa = $total - $totalBayar;
        @endphp
        <tr>
            <td style="border: 1px solid #000000; text-align: center;">{{ $index + 1 }}</td>
            <td style="border: 1px solid #000000;">{{ $item->nomor_faktur }}</td>
            <td style="border: 1px solid #000000; text-align: center;">
                {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
            <td style="border: 1px solid #000000;">{{ $item->supplier_nama }} ({{ $item->supplier_kode }})</td>
            <td style="border: 1px solid #000000; text-align: center;">
                @if ($item->status == 'lunas')
                    LUNAS
                @elseif($item->status == 'sebagian')
                    SEBAGIAN
                @else
                    BELUM BAYAR
                @endif
            </td>
            <td style="border: 1px solid #000000; text-align: right;">{{ $total }}</td>
            <td style="border: 1px solid #000000; text-align: right;">{{ $totalBayar }}</td>
            <td style="border: 1px solid #000000; text-align: right;">{{ $sisa }}</td>
            <td style="border: 1px solid #000000;">{{ $item->nama_petugas }}</td>
            <td style="border: 1px solid #000000;">{{ $item->keterangan }}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="5" style="border: 1px solid #000000; text-align: right; font-weight: bold;">TOTAL</td>
        <td style="border: 1px solid #000000; text-align: right; font-weight: bold;">{{ (float) $totalPembelian }}</td>
        <td style="border: 1px solid #000000; text-align: right; font-weight: bold;">{{ (float) $totalDibayar }}</td>
        <td style="border: 1px solid #000000; text-align: right; font-weight: bold;">{{ (float) $sisaPembayaran }}</td>
        <td colspan="2" style="border: 1px solid #000000;"></td>
    </tr>
</table>
