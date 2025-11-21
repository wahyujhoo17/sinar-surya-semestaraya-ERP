<table>
    <tr>
        <td colspan="8" style="font-size: 16px; font-weight: bold; text-align: center;">LAPORAN PEMBELIAN RINGKAS</td>
    </tr>
    <tr>
        <td colspan="8" style="font-size: 12px; text-align: center;">
            Periode: {{ \Carbon\Carbon::parse($filters['tanggal_awal'] ?? now()->startOfMonth())->format('d M Y') }} s/d
            {{ \Carbon\Carbon::parse($filters['tanggal_akhir'] ?? now())->format('d M Y') }}
        </td>
    </tr>
    <tr>
        <td colspan="8"></td>
    </tr>
    <tr>
        <td colspan="8"></td>
    </tr>
    <tr>
        <td
            style="font-weight: bold; background-color: #1F2937; color: white; text-align: center; border: 2px solid #000000;">
            No</td>
        <td
            style="font-weight: bold; background-color: #1F2937; color: white; text-align: center; border: 2px solid #000000;">
            No Faktur</td>
        <td
            style="font-weight: bold; background-color: #1F2937; color: white; text-align: center; border: 2px solid #000000;">
            Tanggal</td>
        <td
            style="font-weight: bold; background-color: #1F2937; color: white; text-align: center; border: 2px solid #000000;">
            Supplier</td>
        <td
            style="font-weight: bold; background-color: #1F2937; color: white; text-align: center; border: 2px solid #000000;">
            Total Pembelian</td>
        <td
            style="font-weight: bold; background-color: #1F2937; color: white; text-align: center; border: 2px solid #000000;">
            Total Dibayar</td>
        <td
            style="font-weight: bold; background-color: #1F2937; color: white; text-align: center; border: 2px solid #000000;">
            Sisa Pembayaran</td>
        <td
            style="font-weight: bold; background-color: #1F2937; color: white; text-align: center; border: 2px solid #000000;">
            Pembuat</td>
    </tr>
    @php $no = 1; @endphp
    @foreach ($dataPembelian as $po)
        <tr>
            <td style="text-align: center; border: 1px solid #D1D5DB;">{{ $no++ }}</td>
            <td style="border: 1px solid #D1D5DB;">{{ $po->nomor }}</td>
            <td style="text-align: center; border: 1px solid #D1D5DB;">
                {{ \Carbon\Carbon::parse($po->tanggal)->format('d/m/Y') }}</td>
            <td style="border: 1px solid #D1D5DB;">{{ $po->supplier->nama ?? '-' }}</td>
            <td style="text-align: right; border: 1px solid #D1D5DB;">{{ number_format($po->total, 0, ',', '.') }}</td>
            <td style="text-align: right; border: 1px solid #D1D5DB;">{{ number_format($po->total_bayar, 0, ',', '.') }}
            </td>
            <td style="text-align: right; border: 1px solid #D1D5DB;">
                {{ number_format($po->total - $po->total_bayar, 0, ',', '.') }}</td>
            <td style="border: 1px solid #D1D5DB;">{{ $po->user->name ?? '-' }}</td>
        </tr>
    @endforeach
    <tr style="background-color: #DBEAFE; font-weight: bold;">
        <td colspan="4" style="text-align: right; border: 2px solid #000000;">TOTAL</td>
        <td style="text-align: right; border: 2px solid #000000;">{{ number_format($totalPembelian, 0, ',', '.') }}
        </td>
        <td style="text-align: right; border: 2px solid #000000;">{{ number_format($totalDibayar, 0, ',', '.') }}</td>
        <td style="text-align: right; border: 2px solid #000000;">{{ number_format($sisaPembayaran, 0, ',', '.') }}
        </td>
        <td style="border: 2px solid #000000;"></td>
    </tr>
</table>
