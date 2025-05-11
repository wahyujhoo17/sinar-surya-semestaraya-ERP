<table>
    <tr>
        <td colspan="12" style="text-align:center; font-size:16px; font-weight:bold;">LAPORAN STOK BARANG</td>
    </tr>
    <tr>
        <td colspan="12" style="text-align:center;">Periode: {{ \Carbon\Carbon::parse($tanggal_awal)->format('d/m/Y') }}
            s/d {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d/m/Y') }}</td>
    </tr>
    <tr>
        <td colspan="12">Kategori: {{ $kategori }} | Gudang: {{ $gudang }}</td>
    </tr>
    <tr>
        <th>No</th>
        <th>Kode</th>
        <th>Nama Barang</th>
        <th>Kategori</th>
        <th>Satuan</th>
        <th>Gudang</th>
        <th>Stok Awal</th>
        <th>Barang Masuk</th>
        <th>Barang Keluar</th>
        <th>Stok Akhir</th>
        <th>Nilai Barang</th>
        <th>Update Terakhir</th>
    </tr>
    @php
        $no = 1;
        $totalNilai = 0;
    @endphp
    @foreach ($data as $item)
        @php
            $totalNilai += $item['nilai_barang'];
        @endphp
        <tr @if ($item['is_below_minimum']) style="background-color:#FFEB9C;" @endif>
            <td>{{ $no++ }}</td>
            <td>{{ $item['kode_barang'] }}</td>
            <td>{{ $item['nama_barang'] }}</td>
            <td>{{ $item['kategori'] }}</td>
            <td>{{ $item['satuan'] }}</td>
            <td>{{ $item['gudang'] }}</td>
            <td>{{ $item['stok_awal'] }}</td>
            <td>{{ $item['barang_masuk'] }}</td>
            <td>{{ $item['barang_keluar'] }}</td>
            <td>{{ $item['stok_akhir'] }}</td>
            <td>{{ $item['nilai_barang'] }}</td>
            <td>{{ $item['tanggal_update'] ? \Carbon\Carbon::parse($item['tanggal_update'])->format('d/m/Y') : '-' }}
            </td>
        </tr>
    @endforeach
    <tr>
        <td colspan="10" style="text-align:right; font-weight:bold;">TOTAL NILAI BARANG:</td>
        <td style="font-weight:bold;">{{ $totalNilai }}</td>
        <td></td>
    </tr>
</table>
