<table>
    <tr>
        <td colspan="7" style="font-size: 16px; font-weight: bold; text-align: center;">LAPORAN PRODUKSI</td>
    </tr>
    <tr>
        <td colspan="7" style="font-size: 14px; text-align: center;">PT SINAR SURYA SEMESTARAYA</td>
    </tr>
    <tr>
        <td colspan="7" style="font-size: 12px; text-align: center;">Periode:
            {{ \Carbon\Carbon::parse($filters['tanggal_awal'])->format('d M Y') }} -
            {{ \Carbon\Carbon::parse($filters['tanggal_akhir'])->format('d M Y') }}</td>
    </tr>
    <tr></tr>
    <tr>
        <th style="font-weight: bold; background-color: #4F46E5; color: white; text-align: center;">No</th>
        <th style="font-weight: bold; background-color: #4F46E5; color: white; text-align: center;">Nomor</th>
        <th style="font-weight: bold; background-color: #4F46E5; color: white; text-align: center;">Tanggal</th>
        <th style="font-weight: bold; background-color: #4F46E5; color: white; text-align: center;">Produk</th>
        <th style="font-weight: bold; background-color: #4F46E5; color: white; text-align: center;">Jumlah</th>
        <th style="font-weight: bold; background-color: #4F46E5; color: white; text-align: center;">Status</th>
        <th style="font-weight: bold; background-color: #4F46E5; color: white; text-align: center;">Petugas</th>
    </tr>
    @foreach ($dataProduksi as $key => $item)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $item->nomor }}</td>
            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
            <td>{{ $item->produk_nama }} ({{ $item->produk_kode }})</td>
            <td style="text-align: right;">{{ $item->jumlah }}</td>
            <td>
                @if ($item->status == 'selesai')
                    Selesai
                @elseif($item->status == 'proses')
                    Proses
                @elseif($item->status == 'batal')
                    Dibatalkan
                @elseif($item->status == 'menunggu')
                    Menunggu
                @endif
            </td>
            <td>{{ $item->nama_petugas ?? '-' }}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="4" style="font-weight: bold; text-align: right;">Total</td>
        <td style="font-weight: bold; text-align: right;">{{ $totalProduksi }}</td>
        <td colspan="2"></td>
    </tr>
</table>
