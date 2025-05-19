<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\PembayaranHutang;
use App\Models\PurchaseOrder;
use App\Models\ReturPembelian;
use App\Models\Supplier;
use App\Exports\HutangUsahaExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class HutangUsahaController extends Controller
{
    /**
     * Display a listing of hutang usaha.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['supplier', 'details'])
            ->whereIn('status_pembayaran', ['belum_bayar', 'sebagian', 'lunas'])
            ->where('status', '!=', 'dibatalkan')
            ->orderBy('tanggal', 'desc');

        // Filter by supplier if provided
        if ($request->has('supplier_id') && !empty($request->supplier_id)) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Filter by date range
        if ($request->has('start_date') && !empty($request->start_date)) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }

        if ($request->has('end_date') && !empty($request->end_date)) {
            $query->whereDate('tanggal', '<=', $request->end_date);
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

        $suppliers = Supplier::orderBy('nama')->get();

        return view('keuangan.hutang_usaha.index', [
            'purchaseOrders' => $purchaseOrders,
            'suppliers' => $suppliers,
            'request' => $request
        ]);
    }

    /**
     * Display details of a specific purchase order's debt.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $po = PurchaseOrder::with(['supplier', 'details', 'details.produk', 'user'])
            ->findOrFail($id);

        // Get payments for this PO
        $payments = PembayaranHutang::where('purchase_order_id', $id)
            ->with('user')
            ->orderBy('tanggal', 'desc')
            ->get();

        // Get returns for this PO
        $returns = ReturPembelian::where('purchase_order_id', $id)
            ->with(['details', 'details.produk', 'user', 'purchaseOrder.details'])
            ->orderBy('tanggal', 'desc')
            ->get();

        // Calculate totals
        $totalPayments = $payments->sum('jumlah');

        $totalReturValue = 0;
        foreach ($returns as $return) {
            $poDetails = $return->purchaseOrder->details;

            foreach ($return->details as $returDetail) {
                // Find matching PO detail for this product
                $matchingPoDetail = $poDetails->where('produk_id', $returDetail->produk_id)->first();

                if ($matchingPoDetail) {
                    $totalReturValue += $matchingPoDetail->harga * $returDetail->quantity;
                }
            }
        }

        $sisaHutang = $po->total - $totalPayments - $totalReturValue;

        return view('keuangan.hutang_usaha.show', [
            'po' => $po,
            'payments' => $payments,
            'returns' => $returns,
            'totalPayments' => $totalPayments,
            'totalReturValue' => $totalReturValue,
            'sisaHutang' => $sisaHutang
        ]);
    }

    /**
     * Export hutang usaha data to Excel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        return Excel::download(new HutangUsahaExport($request->all()), 'hutang_usaha_' . date('YmdHis') . '.xlsx');
    }

    /**
     * Generate PDF for hutang usaha data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generatePdf(Request $request)
    {
        $query = PurchaseOrder::with(['supplier', 'details'])
            ->whereIn('status_pembayaran', ['belum_bayar', 'sebagian'])
            ->where('status', '!=', 'dibatalkan')
            ->orderBy('tanggal', 'desc');

        // Filter by supplier if provided
        if ($request->has('supplier_id') && !empty($request->supplier_id)) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Filter by date range
        if ($request->has('start_date') && !empty($request->start_date)) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }

        if ($request->has('end_date') && !empty($request->end_date)) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        $purchaseOrders = $query->get();

        // Calculate remaining debt for each PO considering payments and returns
        foreach ($purchaseOrders as $po) {
            $totalPayments = PembayaranHutang::where('purchase_order_id', $po->id)->sum('jumlah');

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

            $po->sisa_hutang = $po->total - $totalPayments - $totalReturValue;
            $po->total_pembayaran = $totalPayments;
            $po->total_retur = $totalReturValue;
        }

        $totalSisaHutang = $purchaseOrders->sum('sisa_hutang');
        $supplier = null;

        if ($request->has('supplier_id') && !empty($request->supplier_id)) {
            $supplier = Supplier::find($request->supplier_id);
        }

        $pdf = PDF::loadView('keuangan.hutang_usaha.pdf', [
            'purchaseOrders' => $purchaseOrders,
            'totalSisaHutang' => $totalSisaHutang,
            'supplier' => $supplier,
            'startDate' => $request->start_date,
            'endDate' => $request->end_date
        ]);

        return $pdf->stream('hutang_usaha_' . date('YmdHis') . '.pdf');
    }

    /**
     * Print a detailed view of a specific purchase order's debt.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print($id)
    {
        $po = PurchaseOrder::with(['supplier', 'details', 'details.produk', 'user'])
            ->findOrFail($id);

        // Get payments for this PO
        $payments = PembayaranHutang::where('purchase_order_id', $id)
            ->with('user')
            ->orderBy('tanggal', 'desc')
            ->get();

        // Get returns for this PO
        $returns = ReturPembelian::where('purchase_order_id', $id)
            ->with(['details', 'details.produk', 'user', 'purchaseOrder.details'])
            ->orderBy('tanggal', 'desc')
            ->get();

        // Calculate totals
        $totalPayments = $payments->sum('jumlah');

        $totalReturValue = 0;
        foreach ($returns as $return) {
            $poDetails = $return->purchaseOrder->details;

            foreach ($return->details as $returDetail) {
                // Find matching PO detail for this product
                $matchingPoDetail = $poDetails->where('produk_id', $returDetail->produk_id)->first();

                if ($matchingPoDetail) {
                    $totalReturValue += $matchingPoDetail->harga * $returDetail->quantity;
                }
            }
        }

        $sisaHutang = $po->total - $totalPayments - $totalReturValue;

        $pdf = PDF::loadView('keuangan.hutang_usaha.print', [
            'po' => $po,
            'payments' => $payments,
            'returns' => $returns,
            'totalPayments' => $totalPayments,
            'totalReturValue' => $totalReturValue,
            'sisaHutang' => $sisaHutang
        ]);

        return $pdf->stream('hutang_usaha_detail_' . $po->nomor . '.pdf');
    }

    /**
     * Display payment history for a specific purchase order.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function history($id)
    {
        $purchaseOrder = PurchaseOrder::with(['supplier', 'details', 'details.produk'])
            ->findOrFail($id);

        // Get payments for this PO
        $pembayaran = PembayaranHutang::where('purchase_order_id', $id)
            ->with('user')
            ->orderBy('tanggal', 'desc')
            ->get();

        // Get returns for this PO
        $retur = ReturPembelian::where('purchase_order_id', $id)
            ->with(['details', 'details.produk', 'user', 'purchaseOrder.details'])
            ->orderBy('tanggal', 'desc')
            ->get();

        // Calculate totals
        $totalPayments = $pembayaran->sum('jumlah');

        $totalReturValue = 0;
        foreach ($retur as $return) {
            $poDetails = $return->purchaseOrder->details;

            foreach ($return->details as $returDetail) {
                // Find matching PO detail for this product
                $matchingPoDetail = $poDetails->where('produk_id', $returDetail->produk_id)->first();

                if ($matchingPoDetail) {
                    $totalReturValue += $matchingPoDetail->harga * $returDetail->quantity;
                }
            }
        }

        // Calculate remaining debt
        $purchaseOrder->total_pembayaran = $totalPayments;
        $purchaseOrder->total_retur = $totalReturValue;
        $purchaseOrder->sisa_hutang = $purchaseOrder->total - $totalPayments - $totalReturValue;

        return view('keuangan.hutang_usaha.history', [
            'purchaseOrder' => $purchaseOrder,
            'pembayaran' => $pembayaran,
            'retur' => $retur
        ]);
    }
}
