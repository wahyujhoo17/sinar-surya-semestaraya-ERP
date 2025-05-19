<?php

namespace App\Exports;

use App\Models\PembayaranHutang;
use App\Models\PurchaseOrder;
use App\Models\ReturPembelian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HutangUsahaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = PurchaseOrder::with(['supplier', 'details'])
            ->whereIn('status_pembayaran', ['belum_bayar', 'sebagian'])
            ->where('status', '!=', 'dibatalkan')
            ->orderBy('tanggal', 'desc');

        // Filter by supplier if provided
        if (isset($this->request['supplier_id']) && !empty($this->request['supplier_id'])) {
            $query->where('supplier_id', $this->request['supplier_id']);
        }

        // Filter by date range
        if (isset($this->request['start_date']) && !empty($this->request['start_date'])) {
            $query->whereDate('tanggal', '>=', $this->request['start_date']);
        }

        if (isset($this->request['end_date']) && !empty($this->request['end_date'])) {
            $query->whereDate('tanggal', '<=', $this->request['end_date']);
        }

        $purchaseOrders = $query->get();

        // Calculate remaining debt for each PO considering payments and returns
        foreach ($purchaseOrders as $po) {
            // Get total payments for this PO
            $totalPayments = PembayaranHutang::where('purchase_order_id', $po->id)->sum('jumlah');

            // Get total returns for this PO
            $returPembelian = ReturPembelian::where('purchase_order_id', $po->id)
                ->where('status', 'selesai')
                ->with(['details', 'purchaseOrder.details'])
                ->get();

            $totalReturValue = 0;
            foreach ($returPembelian as $retur) {
                $poDetails = $retur->purchaseOrder->details;

                foreach ($retur->details as $returDetail) {
                    // Find matching PO detail for this product
                    $matchingPoDetail = $poDetails->where('produk_id', $returDetail->produk_id)->first();

                    if ($matchingPoDetail) {
                        $totalReturValue += $matchingPoDetail->harga * $returDetail->quantity;
                    }
                }
            }

            // Calculate remaining debt
            $po->sisa_hutang = $po->total - $totalPayments - $totalReturValue;
            $po->total_pembayaran = $totalPayments;
            $po->total_retur = $totalReturValue;
        }

        return $purchaseOrders;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nomor PO',
            'Tanggal',
            'Supplier',
            'Total PO',
            'Total Pembayaran',
            'Total Retur',
            'Sisa Hutang',
            'Status Pembayaran',
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($po): array
    {
        $statusPembayaran = '';

        if ($po->status_pembayaran == 'belum_bayar') {
            $statusPembayaran = 'Belum Bayar';
        } elseif ($po->status_pembayaran == 'sebagian') {
            $statusPembayaran = 'Sebagian';
        } else {
            $statusPembayaran = 'Lunas';
        }

        return [
            $po->nomor,
            date('d/m/Y', strtotime($po->tanggal)),
            $po->supplier->nama,
            $po->total,
            $po->total_pembayaran,
            $po->total_retur,
            $po->sisa_hutang,
            $statusPembayaran,
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }
}
