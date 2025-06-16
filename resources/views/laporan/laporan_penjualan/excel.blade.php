{{-- Excel Template for Laporan Penjualan --}}

<table>
    <tr>
        <td colspan="11" style="font-size: 18px; font-weight: bold; text-align: center;">LAPORAN PENJUALAN</td>
    </tr>
    <tr>
        <td colspan="11" style="font-size: 14px; font-weight: bold; text-align: center;">
            Periode:
            {{ \Carbon\Carbon::parse($filters['tanggal_awal'] ?? now()->startOfMonth()->format('Y-m-d'))->format('d/m/Y') }}
            -
            {{ \Carbon\Carbon::parse($filters['tanggal_akhir'] ?? now()->format('Y-m-d'))->format('d/m/Y') }}
        </td>
    </tr>
    <tr>
        <td colspan="11"></td>
    </tr>
    <tr>
        <th>No</th>
        <th>Nomor Faktur</th>
        <th>Tanggal</th>
        <th>Customer</th>
        <th>Status</th>
        <th>Total</th>
        <th>Total Bayar</th>
        <th>Retur</th>
        <th>Sisa</th>
        <th>Petugas</th>
        <th>Keterangan</th>
    </tr>

    @foreach ($dataPenjualan as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->nomor_faktur }}</td>
            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
            <td>{{ $item->customer_nama }} ({{ $item->customer_kode }})</td>
            <td>
                @if ($item->status == 'lunas')
                    LUNAS
                @elseif($item->status == 'sebagian')
                    DIBAYAR SEBAGIAN
                @elseif($item->status == 'belum_bayar')
                    BELUM DIBAYAR
                @elseif($item->status == 'kelebihan_bayar')
                    KELEBIHAN BAYAR
                @endif
            </td>
            <td>{{ number_format($item->total, 0, ',', '.') }}</td>
            <td>{{ number_format($item->total_bayar, 0, ',', '.') }}</td>
            <td>{{ number_format($item->total_retur ?? 0, 0, ',', '.') }}</td>
            <td>{{ number_format($item->total - $item->total_bayar - ($item->total_retur ?? 0), 0, ',', '.') }}</td>
            <td>{{ $item->nama_petugas }}</td>
            <td>{{ $item->keterangan }}</td>
        </tr>
    @endforeach

    {{-- Empty row before total --}}
    <tr>
        <td colspan="11"></td>
    </tr>

    {{-- Total row --}}
    <tr style="font-weight: bold;">
        <td colspan="5" style="text-align: right;">TOTAL:</td>
        <td>{{ number_format($totalPenjualan, 0, ',', '.') }}</td>
        <td>{{ number_format($totalDibayar, 0, ',', '.') }}</td>
        <td>{{ number_format($dataPenjualan->sum('total_retur') ?? 0, 0, ',', '.') }}</td>
        <td>{{ number_format($sisaPembayaran - ($dataPenjualan->sum('total_retur') ?? 0), 0, ',', '.') }}</td>
        <td colspan="2"></td>
    </tr>
</table>
