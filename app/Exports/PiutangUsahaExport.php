<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class PiutangUsahaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
        $query = Invoice::with(['customer', 'salesOrder', 'pembayaranPiutang'])
            ->where('total', '>', 0);

        // Apply all filters from request
        if (!empty($this->filters['customer_id'])) {
            $query->where('customer_id', $this->filters['customer_id']);
        }
        if (!empty($this->filters['sales_id'])) {
            $query->whereHas('customer', function ($q) {
                $q->where('sales_id', $this->filters['sales_id']);
            });
        }
        if (!empty($this->filters['start_date'])) {
            $query->whereDate('tanggal', '>=', $this->filters['start_date']);
        }
        if (!empty($this->filters['end_date'])) {
            $query->whereDate('tanggal', '<=', $this->filters['end_date']);
        }

        // Apply status filter
        if (!empty($this->filters['status_pembayaran_filter'])) {
            $statusFilterUI = $this->filters['status_pembayaran_filter'];
            if ($statusFilterUI !== 'all') {
                $statusMap = [
                    'lunas' => 'lunas',
                    'belum_bayar' => 'belum_bayar',
                    'sebagian' => 'sebagian',
                    'jatuh_tempo' => 'belum_bayar' // Special case, will be further filtered below
                ];
                if (array_key_exists($statusFilterUI, $statusMap)) {
                    $query->where('status', $statusMap[$statusFilterUI]);
                }
            }
        } else {
            $query->whereIn('status', ['belum_bayar', 'sebagian']);
        }

        // Apply sorting if provided
        if (!empty($this->filters['sort']) && !empty($this->filters['direction'])) {
            $allowedColumns = [
                'nomor',
                'tanggal',
                'jatuh_tempo',
                'total',
                'status_pembayaran'
            ];

            if (in_array($this->filters['sort'], $allowedColumns)) {
                $query->orderBy($this->filters['sort'], $this->filters['direction']);
            } else {
                $query->orderBy('tanggal', 'desc');
            }
        } else {
            $query->orderBy('tanggal', 'desc');
        }

        $invoices = $query->get();

        $invoices->transform(function ($invoice) {
            $totalPayments = $invoice->pembayaranPiutang->sum('jumlah');
            $invoice->total_pembayaran_invoice = $totalPayments;
            $invoice->sisa_piutang_invoice = $invoice->sisa_piutang; // Use accessor that includes nota kredit

            // Status display logic for the export
            $sisaPiutang = $invoice->sisa_piutang_invoice;
            $isOverdue = $invoice->jatuh_tempo && Carbon::parse($invoice->jatuh_tempo)->startOfDay()->lt(Carbon::today()->startOfDay());

            if ($sisaPiutang <= 0 && $invoice->status === 'lunas') {
                $invoice->status_display_invoice = 'Lunas';
            } elseif ($isOverdue && $invoice->status !== 'lunas') {
                $invoice->status_display_invoice = 'Jatuh Tempo';
            } elseif ($invoice->status === 'sebagian') {
                $invoice->status_display_invoice = 'Lunas Sebagian';
            } elseif ($invoice->status === 'belum_bayar') {
                $invoice->status_display_invoice = 'Belum Bayar';
            } else if ($sisaPiutang <= 0) {
                $invoice->status_display_invoice = 'Lunas';
            } else {
                $invoice->status_display_invoice = ucfirst(str_replace('_', ' ', $invoice->status ?? 'Tidak Diketahui'));
            }
            return $invoice;
        });

        // If we're filtering for jatuh_tempo
        if (!empty($this->filters['status_pembayaran_filter']) && $this->filters['status_pembayaran_filter'] === 'jatuh_tempo') {
            $invoices = $invoices->filter(function ($invoice) {
                $isOverdue = $invoice->jatuh_tempo && Carbon::parse($invoice->jatuh_tempo)->startOfDay()->lt(Carbon::today()->startOfDay());
                return $isOverdue && $invoice->sisa_piutang_invoice > 0;
            });
        }

        // If no status filter is applied, we might want to further filter out fully paid invoices
        if (empty($this->filters['status_pembayaran_filter'])) {
            $invoices = $invoices->filter(function ($invoice) {
                return $invoice->sisa_piutang_invoice > 0 || $invoice->status !== 'lunas'; // Keep if sisa > 0 OR status is not lunas
            });
        }

        return $invoices;
    }

    public function headings(): array
    {
        return [
            'Nomor Invoice',
            'Tanggal Invoice',
            'Nomor SO',
            'Customer',
            'Total Invoice',
            'Total Pembayaran Diterima (Invoice)',
            'Sisa Piutang (Invoice)',
            'Jatuh Tempo Invoice',
            'Status Invoice',
        ];
    }

    public function map($invoice): array
    {
        return [
            $invoice->nomor,
            Carbon::parse($invoice->tanggal)->format('d/m/Y'),
            $invoice->salesOrder ? $invoice->salesOrder->nomor : '-',
            $invoice->customer->company ?? ($invoice->customer->nama ?? 'N/A'),
            $invoice->total,
            $invoice->total_pembayaran_invoice,
            $invoice->sisa_piutang_invoice,
            $invoice->jatuh_tempo ? Carbon::parse($invoice->jatuh_tempo)->format('d/m/Y') : '-',
            $invoice->status_display_invoice,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
