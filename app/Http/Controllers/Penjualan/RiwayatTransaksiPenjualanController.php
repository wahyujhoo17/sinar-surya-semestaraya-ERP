<?php

namespace App\Http\Controllers\Penjualan;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\SalesOrder;
use App\Models\DeliveryOrder;
use App\Models\Quotation;
use App\Models\ReturPenjualan;
use App\Models\NotaKredit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Laravel\Facades\Excel;
use App\Exports\RiwayatTransaksiPenjualanExport;
use Barryvdh\DomPDF\Facade\Pdf;

class RiwayatTransaksiPenjualanController extends Controller
{
    /**
     * Display a listing of the transaction history.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('penjualan.riwayat_transaksi.index');
    }

    /**
     * Get transaction history data via AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        $search = $request->input('search');
        $periode = $request->input('periode');
        $jenis = $request->input('jenis');
        $status = $request->input('status');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 10);

        // Transform period filter to actual date range
        $dateRange = $this->getDateRange($periode, $startDate, $endDate);
        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];

        // Initialize empty arrays for each transaction type
        $quotations = collect([]);
        $salesOrders = collect([]);
        $deliveryOrders = collect([]);
        $invoices = collect([]);
        $returPenjualans = collect([]);
        $notaKredits = collect([]);

        // Only fetch the requested transaction types, or all if none specified
        if (empty($jenis) || $jenis === 'quotation') {
            $quotations = $this->getQuotations($search, $startDate, $endDate, $status);
        }

        if (empty($jenis) || $jenis === 'sales_order') {
            $salesOrders = $this->getSalesOrders($search, $startDate, $endDate, $status);
        }

        if (empty($jenis) || $jenis === 'delivery_order') {
            $deliveryOrders = $this->getDeliveryOrders($search, $startDate, $endDate, $status);
        }

        if (empty($jenis) || $jenis === 'invoice') {
            $invoices = $this->getInvoices($search, $startDate, $endDate, $status);
        }

        if (empty($jenis) || $jenis === 'retur') {
            $returPenjualans = $this->getReturPenjualans($search, $startDate, $endDate, $status);
        }

        if (empty($jenis) || $jenis === 'nota_kredit') {
            $notaKredits = $this->getNotaKredits($search, $startDate, $endDate, $status);
        }

        // Merge all transaction types into a single collection
        $allTransactions = $quotations
            ->concat($salesOrders)
            ->concat($deliveryOrders)
            ->concat($invoices)
            ->concat($returPenjualans)
            ->concat($notaKredits)
            ->sortByDesc('tanggal');

        // Calculate pagination
        $total = $allTransactions->count();
        $offset = ($page - 1) * $perPage;
        $transactions = $allTransactions->slice($offset, $perPage)->values();

        return response()->json([
            'transactions' => $transactions,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'lastPage' => ceil($total / $perPage),
        ]);
    }

    /**
     * Get date range based on period filter.
     *
     * @param  string  $periode
     * @param  string  $startDate
     * @param  string  $endDate
     * @return array
     */
    private function getDateRange($periode, $startDate, $endDate)
    {
        $today = Carbon::today();

        switch ($periode) {
            case 'today':
                return [
                    'startDate' => $today->format('Y-m-d'),
                    'endDate' => $today->format('Y-m-d'),
                ];
            case 'yesterday':
                $yesterday = $today->copy()->subDay();
                return [
                    'startDate' => $yesterday->format('Y-m-d'),
                    'endDate' => $yesterday->format('Y-m-d'),
                ];
            case 'last7days':
                return [
                    'startDate' => $today->copy()->subDays(6)->format('Y-m-d'),
                    'endDate' => $today->format('Y-m-d'),
                ];
            case 'last30days':
                return [
                    'startDate' => $today->copy()->subDays(29)->format('Y-m-d'),
                    'endDate' => $today->format('Y-m-d'),
                ];
            case 'thisMonth':
                return [
                    'startDate' => $today->copy()->startOfMonth()->format('Y-m-d'),
                    'endDate' => $today->copy()->endOfMonth()->format('Y-m-d'),
                ];
            case 'lastMonth':
                $lastMonth = $today->copy()->subMonth();
                return [
                    'startDate' => $lastMonth->copy()->startOfMonth()->format('Y-m-d'),
                    'endDate' => $lastMonth->copy()->endOfMonth()->format('Y-m-d'),
                ];
            case 'thisYear':
                return [
                    'startDate' => $today->copy()->startOfYear()->format('Y-m-d'),
                    'endDate' => $today->copy()->endOfYear()->format('Y-m-d'),
                ];
            case 'custom':
                return [
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ];
            default:
                // No date filter
                return [
                    'startDate' => null,
                    'endDate' => null,
                ];
        }
    }

    /**
     * Get Quotations that match the filters.
     *
     * @param  string  $search
     * @param  string  $startDate
     * @param  string  $endDate
     * @param  string  $status
     * @return \Illuminate\Support\Collection
     */
    private function getQuotations($search, $startDate, $endDate, $status)
    {
        $query = Quotation::with(['customer', 'user'])
            ->select('id', 'nomor', 'tanggal', 'customer_id', 'total', 'status', 'user_id');

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%")
                            ->orWhere('perusahaan', 'like', "%{$search}%");
                    });
            });
        }

        // Apply date range filter
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        // Apply status filter
        if ($status) {
            $query->where('status', $status);
        }

        return $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'nomor' => $item->nomor,
                'tanggal' => $item->tanggal,
                'jenis' => 'quotation',
                'customer_nama' => $item->customer->nama . ' - ' . $item->customer->perusahaan,
                'total' => $item->total,
                'status' => $item->status,
                'user_nama' => $item->user->name,
            ];
        });
    }

    /**
     * Get Sales Orders that match the filters.
     *
     * @param  string  $search
     * @param  string  $startDate
     * @param  string  $endDate
     * @param  string  $status
     * @return \Illuminate\Support\Collection
     */
    private function getSalesOrders($search, $startDate, $endDate, $status)
    {
        $query = SalesOrder::with(['customer', 'user'])
            ->select('id', 'nomor', 'tanggal', 'customer_id', 'total', 'status_pembayaran', 'status_pengiriman', 'user_id');

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%")
                            ->orWhere('perusahaan', 'like', "%{$search}%");
                    });
            });
        }

        // Apply date range filter
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        // Apply status filter
        if ($status) {
            // Apply status filter to either payment or shipping status depending on what $status represents
            $query->where(function ($q) use ($status) {
                $q->where('status_pembayaran', $status)
                    ->orWhere('status_pengiriman', $status);
            });
        }

        return $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'nomor' => $item->nomor,
                'tanggal' => $item->tanggal,
                'jenis' => 'sales_order',
                'customer_nama' => $item->customer->nama . ' - ' . $item->customer->perusahaan,
                'total' => $item->total,
                'status' => $item->status_pembayaran . '/' . $item->status_pengiriman, // Combine both statuses
                'user_nama' => $item->user->name,
            ];
        });
    }

    /**
     * Get Delivery Orders that match the filters.
     *
     * @param  string  $search
     * @param  string  $startDate
     * @param  string  $endDate
     * @param  string  $status
     * @return \Illuminate\Support\Collection
     */
    private function getDeliveryOrders($search, $startDate, $endDate, $status)
    {
        $query = DeliveryOrder::with(['customer', 'user'])
            ->select('id', 'nomor', 'tanggal', 'customer_id', 'status', 'user_id');

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%")
                            ->orWhere('perusahaan', 'like', "%{$search}%");
                    });
            });
        }

        // Apply date range filter
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        // Apply status filter
        if ($status) {
            $query->where('status', $status);
        }

        return $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'nomor' => $item->nomor,
                'tanggal' => $item->tanggal,
                'jenis' => 'delivery_order',
                'customer_nama' => $item->customer->nama . ' - ' . $item->customer->perusahaan,
                'total' => $item->total ?? 0,
                'status' => $item->status,
                'user_nama' => $item->user->name,
            ];
        });
    }

    /**
     * Get Invoices that match the filters.
     *
     * @param  string  $search
     * @param  string  $startDate
     * @param  string  $endDate
     * @param  string  $status
     * @return \Illuminate\Support\Collection
     */
    private function getInvoices($search, $startDate, $endDate, $status)
    {
        $query = Invoice::with(['customer', 'user'])
            ->select('id', 'nomor', 'tanggal', 'customer_id', 'total', 'status', 'user_id');

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%")
                            ->orWhere('perusahaan', 'like', "%{$search}%");
                    });
            });
        }

        // Apply date range filter
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        // Apply status filter
        if ($status) {
            $query->where('status', $status);
        }

        return $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'nomor' => $item->nomor,
                'tanggal' => $item->tanggal,
                'jenis' => 'invoice',
                'customer_nama' => $item->customer->nama . ' - ' . $item->customer->perusahaan,
                'total' => $item->total,
                'status' => $item->status,
                'user_nama' => $item->user->name,
            ];
        });
    }

    /**
     * Get Retur Penjualans that match the filters.
     *
     * @param  string  $search
     * @param  string  $startDate
     * @param  string  $endDate
     * @param  string  $status
     * @return \Illuminate\Support\Collection
     */
    private function getReturPenjualans($search, $startDate, $endDate, $status)
    {
        $query = ReturPenjualan::with(['customer', 'user'])
            ->select('id', 'nomor', 'tanggal', 'customer_id', 'total', 'status', 'user_id');

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%")
                            ->orWhere('perusahaan', 'like', "%{$search}%");
                    });
            });
        }

        // Apply date range filter
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        // Apply status filter
        if ($status) {
            $query->where('status', $status);
        }

        return $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'nomor' => $item->nomor,
                'tanggal' => $item->tanggal,
                'jenis' => 'retur',
                'customer_nama' => $item->customer->nama . ' - ' . $item->customer->perusahaan,
                'total' => $item->total ?? 0,
                'status' => $item->status,
                'user_nama' => $item->user->name,
            ];
        });
    }

    /**
     * Get Nota Kredits that match the filters.
     *
     * @param  string  $search
     * @param  string  $startDate
     * @param  string  $endDate
     * @param  string  $status
     * @return \Illuminate\Support\Collection
     */
    private function getNotaKredits($search, $startDate, $endDate, $status)
    {
        $query = NotaKredit::with(['customer', 'user'])
            ->select('id', 'nomor', 'tanggal', 'customer_id', 'total', 'status', 'user_id');

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%")
                            ->orWhere('perusahaan', 'like', "%{$search}%");
                    });
            });
        }

        // Apply date range filter
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        // Apply status filter
        if ($status) {
            $query->where('status', $status);
        }

        return $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'nomor' => $item->nomor,
                'tanggal' => $item->tanggal,
                'jenis' => 'nota_kredit',
                'customer_nama' => $item->customer->nama . ' - ' . $item->customer->perusahaan,
                'total' => $item->total,
                'status' => $item->status,
                'user_nama' => $item->user->name,
            ];
        });
    }

    /**
     * Export data to Excel or PDF.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $type
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request, $type)
    {
        $search = $request->input('search');
        $periode = $request->input('periode');
        $jenis = $request->input('jenis');
        $status = $request->input('status');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Transform period filter to actual date range
        $dateRange = $this->getDateRange($periode, $startDate, $endDate);
        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];

        $filters = [
            'search' => $search,
            'periode' => $periode,
            'jenis' => $jenis,
            'status' => $status,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        if ($type === 'excel') {
            // Format the date for the filename
            $fileDate = Carbon::now()->format('Ymd_His');
            $fileName = "riwayat_transaksi_penjualan_{$fileDate}.xlsx";

            return Excel::download(new RiwayatTransaksiPenjualanExport($filters), $fileName);
        } else {
            // For PDF export, fetch the data and pass to the view
            $quotations = $this->getQuotations($search, $startDate, $endDate, $status);
            $salesOrders = $this->getSalesOrders($search, $startDate, $endDate, $status);
            $deliveryOrders = $this->getDeliveryOrders($search, $startDate, $endDate, $status);
            $invoices = $this->getInvoices($search, $startDate, $endDate, $status);
            $returPenjualans = $this->getReturPenjualans($search, $startDate, $endDate, $status);
            $notaKredits = $this->getNotaKredits($search, $startDate, $endDate, $status);

            // Merge all transaction types into a single collection
            $transactions = $quotations
                ->concat($salesOrders)
                ->concat($deliveryOrders)
                ->concat($invoices)
                ->concat($returPenjualans)
                ->concat($notaKredits)
                ->sortByDesc('tanggal');

            // Format the period for display in the PDF
            $periodText = $this->getPeriodText($periode, $startDate, $endDate);

            $pdf = PDF::loadView('penjualan.riwayat_transaksi.pdf', [
                'transactions' => $transactions,
                'periode' => $periodText,
                'search' => $search,
                'jenis' => $jenis,
                'status' => $status
            ]);

            // Set paper to landscape for better readability with many columns
            $pdf->setPaper('a4', 'landscape');

            // Format the date for the filename
            $fileDate = Carbon::now()->format('Ymd_His');
            $fileName = "riwayat_transaksi_penjualan_{$fileDate}.pdf";

            return $pdf->download($fileName);
        }
    }

    /**
     * Get a human-readable period text for PDF display.
     *
     * @param  string  $periode
     * @param  string  $startDate
     * @param  string  $endDate
     * @return string
     */
    private function getPeriodText($periode, $startDate, $endDate)
    {
        switch ($periode) {
            case 'today':
                return 'Hari Ini (' . Carbon::today()->format('d/m/Y') . ')';
            case 'yesterday':
                return 'Kemarin (' . Carbon::yesterday()->format('d/m/Y') . ')';
            case 'last7days':
                return '7 Hari Terakhir (' . Carbon::today()->subDays(6)->format('d/m/Y') . ' - ' . Carbon::today()->format('d/m/Y') . ')';
            case 'last30days':
                return '30 Hari Terakhir (' . Carbon::today()->subDays(29)->format('d/m/Y') . ' - ' . Carbon::today()->format('d/m/Y') . ')';
            case 'thisMonth':
                return 'Bulan Ini (' . Carbon::today()->startOfMonth()->format('d/m/Y') . ' - ' . Carbon::today()->endOfMonth()->format('d/m/Y') . ')';
            case 'lastMonth':
                $lastMonth = Carbon::today()->subMonth();
                return 'Bulan Lalu (' . $lastMonth->startOfMonth()->format('d/m/Y') . ' - ' . $lastMonth->endOfMonth()->format('d/m/Y') . ')';
            case 'thisYear':
                return 'Tahun Ini (' . Carbon::today()->startOfYear()->format('d/m/Y') . ' - ' . Carbon::today()->endOfYear()->format('d/m/Y') . ')';
            case 'custom':
                if ($startDate && $endDate) {
                    return 'Kustom (' . Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y') . ')';
                }
                return 'Kustom';
            default:
                return 'Semua Waktu';
        }
    }
}
