<table>
    <tr>
        <td colspan="7" style="font-size: 16px; font-weight: bold; text-align: center;">LAPORAN PENJUALAN RINGKAS</td>
    </tr>
    <tr>
        <td colspan="7" style="font-size: 12px; text-align: center;">
            Periode: {{ \Carbon\Carbon::parse($filters['tanggal_awal'] ?? now()->startOfMonth())->format('d M Y') }} s/d
            {{ \Carbon\Carbon::parse($filters['tanggal_akhir'] ?? now())->format('d M Y') }}
        </td>
    </tr>
    <tr>
        <td colspan="7"></td>
    </tr>
    <tr>
        <td colspan="7"></td>
    </tr>
    <tr>
        <td style="font-weight: bold; background-color: #4F46E5; color: white; text-align: center;">No</td>
        <td style="font-weight: bold; background-color: #4F46E5; color: white; text-align: center;">Customer</td>
        <td style="font-weight: bold; background-color: #4F46E5; color: white; text-align: center;">Jumlah Transaksi
        </td>
        <td style="font-weight: bold; background-color: #4F46E5; color: white; text-align: center;">Total Penjualan
        </td>
        <td style="font-weight: bold; background-color: #4F46E5; color: white; text-align: center;">Total Dibayar</td>
        <td style="font-weight: bold; background-color: #4F46E5; color: white; text-align: center;">Uang Muka</td>
        <td style="font-weight: bold; background-color: #4F46E5; color: white; text-align: center;">Sisa Pembayaran
        </td>
    </tr>
    @php $no = 1; @endphp
    @foreach ($groupedData as $data)
        <tr>
            <td style="text-align: center;">{{ $no++ }}</td>
            <td>{{ $data['customer']->company ?? ($data['customer']->nama ?? 'Unknown') }}<br>{{ $data['customer']->kode ?? '-' }}
            </td>
            <td style="text-align: center;">{{ $data['total_transaksi'] }}</td>
            <td style="text-align: right;">{{ $data['total_penjualan'] }}</td>
            <td style="text-align: right;">{{ $data['total_dibayar'] }}</td>
            <td style="text-align: right;">{{ $data['total_uang_muka'] }}</td>
            <td style="text-align: right;">{{ $data['sisa_pembayaran'] }}</td>
        </tr>
    @endforeach
    <tr style="background-color: #DBEAFE; font-weight: bold;">
        <td colspan="2" style="text-align: right;">TOTAL</td>
        <td style="text-align: center;">{{ $groupedData->sum('total_transaksi') }}</td>
        <td style="text-align: right;">{{ $totalPenjualan }}</td>
        <td style="text-align: right;">{{ $totalDibayar }}</td>
        <td style="text-align: right;">{{ $totalUangMuka }}</td>
        <td style="text-align: right;">{{ $sisaPembayaran }}</td>
    </tr>
</table>
