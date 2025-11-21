<table>
    <tr>
        <td colspan="10" style="font-size: 16px; font-weight: bold; text-align: center;">LAPORAN PENJUALAN RINGKAS</td>
    </tr>
    <tr>
        <td colspan="10" style="font-size: 12px; text-align: center;">
            Periode: {{ \Carbon\Carbon::parse($filters['tanggal_awal'] ?? now()->startOfMonth())->format('d M Y') }} s/d
            {{ \Carbon\Carbon::parse($filters['tanggal_akhir'] ?? now())->format('d M Y') }}
        </td>
    </tr>
    <tr>
        <td colspan="10"></td>
    </tr>
    <tr>
        <td colspan="10"></td>
    </tr>
    <tr>
        <td style="font-weight: bold; background-color: #4F46E5; color: white; text-align: center;">No</td>
        <td style="font-weight: bold; background-color: #4F46E5; color: white; text-align: center;">Tanggal</td>
        <td style="font-weight: bold; background-color: #4F46E5; color: white; text-align: center;">No SO</td>
        <td style="font-weight: bold; background-color: #4F46E5; color: white; text-align: center;">No Inv</td>
        <td style="font-weight: bold; background-color: #4F46E5; color: white; text-align: center;">No PO</td>
        <td style="font-weight: bold; background-color: #4F46E5; color: white; text-align: center;">Customer</td>
        <td style="font-weight: bold; background-color: #4F46E5; color: white; text-align: center;">Total Penjualan</td>
        <td style="font-weight: bold; background-color: #4F46E5; color: white; text-align: center;">Total Dibayar</td>
        <td style="font-weight: bold; background-color: #4F46E5; color: white; text-align: center;">Uang Muka</td>
        <td style="font-weight: bold; background-color: #4F46E5; color: white; text-align: center;">Sisa Pembayaran</td>
    </tr>
    @php $no = 1; @endphp
    @foreach ($dataPenjualan as $data)
        <tr>
            <td style="text-align: center;">{{ $no++ }}</td>
            <td style="text-align: center;">{{ \Carbon\Carbon::parse($data->tanggal)->format('d/m/Y') }}</td>
            <td style="text-align: center;">{{ $data->nomor }}</td>
            <td style="text-align: center;">{{ $data->nomor_invoice ?: '-' }}</td>
            <td style="text-align: center;">{{ $data->nomor_po ?: '-' }}</td>
            <td>{{ $data->customer->company ?? ($data->customer->nama ?? 'Unknown') }}<br>{{ $data->customer->kode ?? '-' }}
            </td>
            <td style="text-align: right;">   {{ number_format($data->total, 2, ',', '.') }}</td>
            <td style="text-align: right;">   {{ number_format($data->total_bayar, 2, ',', '.') }}</td>
            <td style="text-align: right;">   {{ number_format($data->total_uang_muka, 2, ',', '.') }}</td>
            <td style="text-align: right;">   {{ number_format($data->total - $data->total_bayar, 2, ',', '.') }}
            </td>
        </tr>
    @endforeach
    <tr style="background-color: #DBEAFE; font-weight: bold;">
        <td colspan="6" style="text-align: right;">TOTAL</td>
        <td style="text-align: right;">   {{ number_format($totalPenjualan, 2, ',', '.') }}</td>
        <td style="text-align: right;">   {{ number_format($totalDibayar, 2, ',', '.') }}</td>
        <td style="text-align: right;">   {{ number_format($totalUangMuka, 2, ',', '.') }}</td>
        <td style="text-align: right;">   {{ number_format($sisaPembayaran, 2, ',', '.') }}</td>
    </tr>
</table>
