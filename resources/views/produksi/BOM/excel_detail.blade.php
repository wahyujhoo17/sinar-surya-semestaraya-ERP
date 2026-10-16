<table>
    <tr>
        <td colspan="8" style="font-size: 16px; font-weight: bold; text-align: center;">BILL OF MATERIAL (BOM) DETAIL</td>
    </tr>
    <tr>
        <td colspan="8" style="font-size: 13px; font-weight: bold; text-align: center;">PT. SINAR SURYA SEMESTARAYA</td>
    </tr>
    <tr>
        <td colspan="8"></td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Kode BOM</td>
        <td>: {{ $bom->kode }}</td>
        <td style="font-weight: bold;">Produk Jadi</td>
        <td colspan="5">: {{ $bom->produk->nama ?? '-' }} ({{ $bom->produk->kode ?? '-' }})</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Nama BOM</td>
        <td>: {{ $bom->nama }}</td>
        <td style="font-weight: bold;">Kategori / Satuan</td>
        <td colspan="5">: {{ $bom->produk->kategori->nama ?? '-' }} / {{ $bom->produk->satuan->nama ?? '-' }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Versi</td>
        <td>: {{ $bom->versi }}</td>
        <td style="font-weight: bold;">Tanggal Dibuat</td>
        <td colspan="5">: {{ $bom->created_at ? $bom->created_at->format('d/m/Y H:i') : '-' }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Status</td>
        <td>: {{ $bom->is_active ? 'Aktif' : 'Non Aktif' }}</td>
        <td style="font-weight: bold;">Dibuat Oleh</td>
        <td colspan="5">: {{ $bom->creator_name }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Deskripsi</td>
        <td colspan="7">: {{ $bom->deskripsi ?? '-' }}</td>
    </tr>
    <tr>
        <td colspan="8"></td>
    </tr>
    <tr>
        <td colspan="8" style="font-size: 12px; font-weight: bold; background-color: #E5E7EB;">DAFTAR KOMPONEN DAN BAHAN BAKU</td>
    </tr>
    <tr>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center;">No</th>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center;">Kode Komponen</th>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center;">Nama Bahan Baku / Komponen</th>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center;">Qty</th>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center;">Satuan</th>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center;">Harga Beli (Rp)</th>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center;">Subtotal (Rp)</th>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center;">Catatan</th>
    </tr>
    @forelse($bom->details as $index => $detail)
        <tr>
            <td style="text-align: center;">{{ $index + 1 }}</td>
            <td>{{ $detail->komponen->kode ?? '-' }}</td>
            <td>{{ $detail->komponen->nama ?? '-' }}</td>
            <td style="text-align: center;">{{ $detail->quantity }}</td>
            <td style="text-align: center;">{{ $detail->satuan->nama ?? ($detail->komponen->satuan->nama ?? '-') }}</td>
            <td style="text-align: right;">{{ $detail->komponen->harga_beli ?? 0 }}</td>
            <td style="text-align: right;">{{ $detail->calculated_subtotal }}</td>
            <td>{{ $detail->catatan ?? '-' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="8" style="text-align: center;">Belum ada komponen pada BOM ini.</td>
        </tr>
    @endforelse
    <tr>
        <td colspan="6" style="font-weight: bold; text-align: right; background-color: #F3F4F6;">Subtotal Biaya Bahan Baku:</td>
        <td style="font-weight: bold; text-align: right; background-color: #F3F4F6;">{{ $totalMaterialCost }}</td>
        <td style="background-color: #F3F4F6;"></td>
    </tr>
    <tr>
        <td colspan="6" style="font-weight: bold; text-align: right; background-color: #F3F4F6;">Biaya Overhead Pabrik:</td>
        <td style="font-weight: bold; text-align: right; background-color: #F3F4F6;">{{ $overheadCost }}</td>
        <td style="background-color: #F3F4F6;"></td>
    </tr>
    <tr>
        <td colspan="6" style="font-weight: bold; text-align: right; background-color: #DBEAFE; color: #1E3A8A;">TOTAL HPP / ESTIMASI BIAYA PRODUKSI:</td>
        <td style="font-weight: bold; text-align: right; background-color: #DBEAFE; color: #1E3A8A;">{{ $grandTotalCost }}</td>
        <td style="background-color: #DBEAFE;"></td>
    </tr>
    <tr>
        <td colspan="8"></td>
    </tr>
    <tr>
        <td colspan="2" style="font-weight: bold;">Dibuat Oleh:</td>
        <td colspan="6">{{ $bom->creator_name }}</td>
    </tr>
</table>
