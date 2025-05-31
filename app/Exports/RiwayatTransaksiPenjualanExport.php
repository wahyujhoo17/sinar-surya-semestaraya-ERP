<?php

namespace App\Exports;

use App\Models\Quotation;
use App\Models\SalesOrder;
use App\Models\DeliveryOrder;
use App\Models\Invoice;
use App\Models\ReturPenjualan;
use App\Models\NotaKredit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class RiwayatTransaksiPenjualanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $search = $this->filters['search'] ?? null;
        $periode = $this->filters['periode'] ?? null;
        $jenis = $this->filters['jenis'] ?? null;
        $status = $this->filters['status'] ?? null;
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        // Transform period filter to actual date range
        if ($periode) {
            $dateRange = $this->getDateRange($periode, $startDate, $endDate);
            $startDate = $dateRange['startDate'];
            $endDate = $dateRange['endDate'];
        }

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
        return $quotations
            ->concat($salesOrders)
            ->concat($deliveryOrders)
            ->concat($invoices)
            ->concat($returPenjualans)
            ->concat($notaKredits)
            ->sortByDesc('tanggal');
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Tanggal',
            'No. Dokumen',
            'Jenis Transaksi',
            'Customer',
            'Total',
            'Status',
            'User'
        ];
    }

    /**
     * @param mixed $transaksi
     * @return array
     */
    public function map($transaksi): array
    {
        $jenisLabels = [
            'quotation' => 'Quotation',
            'sales_order' => 'Sales Order',
            'delivery_order' => 'Delivery Order',
            'invoice' => 'Invoice',
            'retur' => 'Retur Penjualan',
            'nota_kredit' => 'Nota Kredit'
        ];

        $statusLabels = [
            'draft' => 'Draft',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            'belum_bayar' => 'Belum Bayar',
            'sebagian' => 'Bayar Sebagian',
            'lunas' => 'Lunas'
        ];

        return [
            Carbon::parse($transaksi['tanggal'])->format('d/m/Y'),
            $transaksi['nomor'],
            $jenisLabels[$transaksi['jenis']] ?? $transaksi['jenis'],
            $transaksi['customer_nama'],
            $transaksi['total'],
            $statusLabels[$transaksi['status']] ?? $transaksi['status'],
            $transaksi['user_nama']
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
                'status' => $item->status_pembayaran, // Using payment status as the primary status
                'user_nama' => $item->user->name,
            ];
        });
    }

    /**
     * Get Delivery Orders that match the filters.
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
     */
    private function getReturPenjualans($search, $startDate, $endDate, $status)
    {
        $query = ReturPenjualan::with(['customer', 'user'])
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
}
