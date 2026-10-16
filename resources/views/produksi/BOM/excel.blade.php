<table>
    <tr>
        <td colspan="22" style="font-size: 16px; font-weight: bold; text-align: center;">LAPORAN BILL OF MATERIAL (BOM) &amp; DETAIL KOMPONEN</td>
    </tr>
    <tr>
        <td colspan="22" style="font-size: 13px; font-weight: bold; text-align: center;">PT. SINAR SURYA SEMESTARAYA</td>
    </tr>
    <tr>
        <td colspan="22" style="font-size: 11px; text-align: center; color: #4B5563;">
            Tanggal Ekspor: {{ now()->translatedFormat('d F Y H:i') }} | Total: {{ $totalBOM }} BOM ({{ $activeBOM }} Aktif, {{ $inactiveBOM }} Non-Aktif) | Total Komponen: {{ $totalComponentsCount }} item
        </td>
    </tr>
    @php
        $filterTexts = [];
        if (!empty($filters['start_date']) || !empty($filters['end_date'])) {
            $start = !empty($filters['start_date']) ? \Carbon\Carbon::parse($filters['start_date'])->format('d/m/Y') : 'Awal';
            $end = !empty($filters['end_date']) ? \Carbon\Carbon::parse($filters['end_date'])->format('d/m/Y') : 'Sekarang';
            $filterTexts[] = "Periode: {$start} s/d {$end}";
        }
        if (!empty($filters['status']) || (isset($filters['status']) && $filters['status'] === '0')) {
            $filterTexts[] = "Status: " . ($filters['status'] == '1' ? 'Aktif' : 'Non Aktif');
        }
        if (!empty($filters['search_query']) || !empty($filters['search'])) {
            $filterTexts[] = "Pencarian: " . ($filters['search_query'] ?? $filters['search']);
        }
        if (!empty($filters['cost_status'])) {
            $costLabel = match($filters['cost_status']) {
                'ready' => 'Siap Produksi (Ada Biaya)',
                'no_cost' => 'Belum Ada Biaya',
                'empty' => 'BOM Tanpa Komponen',
                default => $filters['cost_status']
            };
            $filterTexts[] = "Status Biaya: {$costLabel}";
        }
    @endphp
    <tr>
        <td colspan="22" style="font-size: 10px; font-style: italic; text-align: center; color: #6B7280;">
            @if(count($filterTexts) > 0)
                Filter Diterapkan: {{ implode(' | ', $filterTexts) }}
            @else
                Filter: Semua Data (Tanpa Filter Tambahan)
            @endif
        </td>
    </tr>
    <tr>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000;">No</th>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000;">Kode BOM</th>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000;">Nama BOM</th>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000;">Versi</th>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000;">Status BOM</th>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000;">Produk Jadi</th>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000;">Kategori Produk</th>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000;">Satuan Produk</th>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000;">No Komp</th>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000;">Kode Komponen</th>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000;">Nama Bahan Baku / Komponen</th>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000;">Kategori Komponen</th>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000;">Qty Komponen</th>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000;">Satuan Komponen</th>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000;">Harga Beli Satuan (Rp)</th>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000;">Subtotal Bahan (Rp)</th>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000;">Catatan Komponen</th>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000;">Biaya Overhead (Rp)</th>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000;">Total HPP BOM (Rp)</th>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000;">Tanggal Dibuat</th>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000;">Pembuat BOM</th>
        <th style="background-color: #1E3A8A; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000;">Keterangan BOM</th>
    </tr>
    @forelse($boms as $bomIndex => $bom)
        @php
            $details = $bom->details;
            $detailCount = $details->count();
            $rowCount = max(1, $detailCount);
        @endphp

        @if($detailCount > 0)
            @foreach($details as $idx => $detail)
                <tr>
                    @if($idx === 0)
                        <td rowspan="{{ $rowCount }}" style="text-align: center; vertical-align: top; border: 1px solid #D1D5DB;">{{ $bomIndex + 1 }}</td>
                        <td rowspan="{{ $rowCount }}" style="font-weight: bold; vertical-align: top; border: 1px solid #D1D5DB;">{{ $bom->kode }}</td>
                        <td rowspan="{{ $rowCount }}" style="vertical-align: top; border: 1px solid #D1D5DB;">{{ $bom->nama }}</td>
                        <td rowspan="{{ $rowCount }}" style="text-align: center; vertical-align: top; border: 1px solid #D1D5DB;">{{ $bom->versi ?? '1.0' }}</td>
                        <td rowspan="{{ $rowCount }}" style="text-align: center; vertical-align: top; font-weight: bold; color: {{ $bom->is_active ? '#065F46' : '#991B1B' }}; border: 1px solid #D1D5DB;">
                            {{ $bom->is_active ? 'Aktif' : 'Non Aktif' }}
                        </td>
                        <td rowspan="{{ $rowCount }}" style="vertical-align: top; border: 1px solid #D1D5DB;">
                            {{ $bom->produk->nama ?? ($bom->produk->kode ?? '-') }}
                        </td>
                        <td rowspan="{{ $rowCount }}" style="vertical-align: top; border: 1px solid #D1D5DB;">{{ $bom->produk->kategori->nama ?? '-' }}</td>
                        <td rowspan="{{ $rowCount }}" style="text-align: center; vertical-align: top; border: 1px solid #D1D5DB;">{{ $bom->produk->satuan->nama ?? '-' }}</td>
                    @endif

                    <td style="text-align: center; border: 1px solid #D1D5DB;">{{ $idx + 1 }}</td>
                    <td style="border: 1px solid #D1D5DB;">{{ $detail->komponen->kode ?? '-' }}</td>
                    <td style="border: 1px solid #D1D5DB;">{{ $detail->komponen->nama ?? '-' }}</td>
                    <td style="border: 1px solid #D1D5DB;">{{ $detail->komponen->kategori->nama ?? '-' }}</td>
                    <td style="text-align: right; border: 1px solid #D1D5DB;">{{ (float) $detail->quantity }}</td>
                    <td style="text-align: center; border: 1px solid #D1D5DB;">{{ $detail->satuan->nama ?? ($detail->komponen->satuan->nama ?? '-') }}</td>
                    <td style="text-align: right; border: 1px solid #D1D5DB;">{{ (float) ($detail->komponen->harga_beli ?? 0) }}</td>
                    <td style="text-align: right; border: 1px solid #D1D5DB;">{{ (float) ($detail->calculated_subtotal ?? 0) }}</td>
                    <td style="border: 1px solid #D1D5DB;">{{ $detail->catatan ?? '-' }}</td>

                    @if($idx === 0)
                        <td rowspan="{{ $rowCount }}" style="text-align: right; vertical-align: top; border: 1px solid #D1D5DB;">{{ (float) ($bom->calculated_overhead_cost ?? 0) }}</td>
                        <td rowspan="{{ $rowCount }}" style="text-align: right; font-weight: bold; vertical-align: top; border: 1px solid #D1D5DB;">{{ (float) ($bom->calculated_total_cost ?? 0) }}</td>
                        <td rowspan="{{ $rowCount }}" style="text-align: center; vertical-align: top; border: 1px solid #D1D5DB;">{{ $bom->created_at ? $bom->created_at->format('d/m/Y') : '-' }}</td>
                        <td rowspan="{{ $rowCount }}" style="vertical-align: top; border: 1px solid #D1D5DB;">{{ $bom->creator_name }}</td>
                        <td rowspan="{{ $rowCount }}" style="vertical-align: top; border: 1px solid #D1D5DB;">{{ $bom->deskripsi ?? '-' }}</td>
                    @endif
                </tr>
            @endforeach
        @else
            <tr>
                <td style="text-align: center; vertical-align: top; border: 1px solid #D1D5DB;">{{ $bomIndex + 1 }}</td>
                <td style="font-weight: bold; vertical-align: top; border: 1px solid #D1D5DB;">{{ $bom->kode }}</td>
                <td style="vertical-align: top; border: 1px solid #D1D5DB;">{{ $bom->nama }}</td>
                <td style="text-align: center; vertical-align: top; border: 1px solid #D1D5DB;">{{ $bom->versi ?? '1.0' }}</td>
                <td style="text-align: center; vertical-align: top; font-weight: bold; color: {{ $bom->is_active ? '#065F46' : '#991B1B' }}; border: 1px solid #D1D5DB;">
                    {{ $bom->is_active ? 'Aktif' : 'Non Aktif' }}
                </td>
                <td style="vertical-align: top; border: 1px solid #D1D5DB;">
                    {{ $bom->produk->nama ?? ($bom->produk->kode ?? '-') }}
                </td>
                <td style="vertical-align: top; border: 1px solid #D1D5DB;">{{ $bom->produk->kategori->nama ?? '-' }}</td>
                <td style="text-align: center; vertical-align: top; border: 1px solid #D1D5DB;">{{ $bom->produk->satuan->nama ?? '-' }}</td>
                <td style="text-align: center; border: 1px solid #D1D5DB;">-</td>
                <td colspan="8" style="text-align: center; font-style: italic; color: #6B7280; border: 1px solid #D1D5DB;">(Belum ada komponen terdaftar)</td>
                <td style="text-align: right; vertical-align: top; border: 1px solid #D1D5DB;">{{ (float) ($bom->calculated_overhead_cost ?? 0) }}</td>
                <td style="text-align: right; font-weight: bold; vertical-align: top; border: 1px solid #D1D5DB;">{{ (float) ($bom->calculated_total_cost ?? 0) }}</td>
                <td style="text-align: center; vertical-align: top; border: 1px solid #D1D5DB;">{{ $bom->created_at ? $bom->created_at->format('d/m/Y') : '-' }}</td>
                <td style="vertical-align: top; border: 1px solid #D1D5DB;">{{ $bom->creator_name }}</td>
                <td style="vertical-align: top; border: 1px solid #D1D5DB;">{{ $bom->deskripsi ?? '-' }}</td>
            </tr>
        @endif
    @empty
        <tr>
            <td colspan="22" style="text-align: center; padding: 20px; color: #6B7280;">Tidak ada data BOM yang sesuai dengan filter.</td>
        </tr>
    @endforelse
    @if($boms->count() > 0)
        <tr>
            <td colspan="12" style="background-color: #F3F4F6; font-weight: bold; text-align: right; border: 1px solid #D1D5DB;">TOTAL KESELURUHAN ({{ $boms->count() }} BOM, {{ $totalComponentsCount }} KOMPONEN):</td>
            <td style="background-color: #F3F4F6; font-weight: bold; text-align: right; border: 1px solid #D1D5DB;">-</td>
            <td colspan="2" style="background-color: #F3F4F6; border: 1px solid #D1D5DB;"></td>
            <td style="background-color: #F3F4F6; font-weight: bold; text-align: right; border: 1px solid #D1D5DB;">{{ (float) $totalMaterialCost }}</td>
            <td style="background-color: #F3F4F6; border: 1px solid #D1D5DB;"></td>
            <td style="background-color: #F3F4F6; font-weight: bold; text-align: right; border: 1px solid #D1D5DB;">{{ (float) $totalOverheadCost }}</td>
            <td style="background-color: #DBEAFE; font-weight: bold; text-align: right; color: #1E3A8A; border: 1px solid #D1D5DB;">{{ (float) $grandTotalCost }}</td>
            <td colspan="3" style="background-color: #F3F4F6; border: 1px solid #D1D5DB;"></td>
        </tr>
    @endif
    <tr>
        <td colspan="22"></td>
    </tr>
    <tr>
        <td colspan="3" style="font-weight: bold;">Dicetak Oleh:</td>
        <td colspan="19">{{ auth()->user()->name ?? 'Administrator' }} ({{ now()->translatedFormat('d F Y H:i') }})</td>
    </tr>
</table>
