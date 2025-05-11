<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PembayaranHutang;
use App\Models\ReturPembelian;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    /**
     * Get unpaid or partially paid purchase orders for a supplier
     */
    public function getBySupplier($supplierId)
    {
        $purchaseOrders = PurchaseOrder::where('supplier_id', $supplierId)
            ->whereIn('status_pembayaran', ['belum_bayar', 'sebagian'])
            ->orderBy('tanggal', 'desc')
            ->get();

        $result = [];

        foreach ($purchaseOrders as $po) {
            // Calculate payments made
            $totalPayments = PembayaranHutang::where('purchase_order_id', $po->id)->sum('jumlah');

            // Calculate returns
            $returPembelian = ReturPembelian::where('purchase_order_id', $po->id)
                ->where('status', 'selesai')
                ->with('details')
                ->get();

            $totalReturValue = 0;
            foreach ($returPembelian as $retur) {
                foreach ($retur->details as $detail) {
                    $totalReturValue += $detail->harga * $detail->qty;
                }
            }

            $sisaHutang = $po->total - $totalPayments - $totalReturValue;

            // Only include PO if there's remaining debt
            if ($sisaHutang > 0) {
                $result[] = [
                    'id' => $po->id,
                    'nomor' => $po->nomor,
                    'tanggal' => $po->tanggal,
                    'total' => $po->total,
                    'sisa_hutang' => $sisaHutang
                ];
            }
        }

        return response()->json($result);
    }
}
