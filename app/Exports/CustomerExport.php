<?php

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class CustomerExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Check if current user can access all customers (admin/manager_penjualan/admin_penjualan)
     */
    private function canAccessAllCustomers()
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        return $user->hasRole('admin') || $user->hasRole('manager_penjualan') || $user->hasRole('admin_penjualan');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Customer::with(['sales.karyawan']);

        if (!$this->canAccessAllCustomers()) {
            $user = Auth::user();
            $query->where(function ($q) use ($user) {
                $q->where('sales_id', $user->id)
                  ->orWhere('sales_name', $user->name);
            });
        } elseif (!empty($this->filters['sales_id'])) {
            if ($this->filters['sales_id'] === 'none') {
                $query->whereNull('sales_id');
            } else {
                $query->where('sales_id', $this->filters['sales_id']);
            }
        }

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                    ->orWhere('kode', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('telepon', 'like', "%$search%")
                    ->orWhere('company', 'like', "%$search%");
            });
        }

        if (!empty($this->filters['tipe'])) {
            $query->where('tipe', $this->filters['tipe']);
        }

        if (isset($this->filters['is_active']) && $this->filters['is_active'] !== '') {
            $query->where('is_active', $this->filters['is_active']);
        }

        return $query->orderBy('nama', 'asc')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Kode Pelanggan',
            'Nama Pelanggan',
            'Tipe',
            'Perusahaan',
            'Grup',
            'Industri',
            'Sales',
            'Jalan',
            'Kota',
            'Provinsi',
            'Kode Pos',
            'Negara',
            'Alamat Lengkap',
            'Alamat Pengiriman',
            'Telepon',
            'Email',
            'NPWP',
            'Kontak Person',
            'Jabatan Kontak',
            'No. HP Kontak',
            'Catatan',
            'Status',
            'Tanggal Dibuat',
            'Terakhir Diupdate'
        ];
    }

    /**
     * @param Customer $customer
     * @return array
     */
    public function map($customer): array
    {
        static $no = 1;

        // Mendapatkan nama lengkap Sales
        $salesName = '-';
        if ($customer->sales) {
            $salesName = $customer->sales->display_name;
        } elseif (!empty($customer->sales_name)) {
            $salesName = $customer->sales_name;
        }

        $formatDate = function ($date) {
            if (!$date) return '-';
            if ($date instanceof \Carbon\Carbon) {
                return $date->format('d/m/Y H:i');
            }
            try {
                return \Carbon\Carbon::parse($date)->format('d/m/Y H:i');
            } catch (\Exception $e) {
                return $date;
            }
        };

        return [
            $no++,
            $customer->kode ?? '-',
            $customer->nama ?? '-',
            $customer->tipe ?? '-',
            $customer->company ?? '-',
            $customer->group ?? '-',
            $customer->industri ?? '-',
            $salesName,
            $customer->jalan ?? '-',
            $customer->kota ?? '-',
            $customer->provinsi ?? '-',
            $customer->kode_pos ?? '-',
            $customer->negara ?? '-',
            $customer->alamat ?? '-',
            $customer->alamat_pengiriman ?? '-',
            $customer->telepon ?? '-',
            $customer->email ?? '-',
            $customer->npwp ?? '-',
            $customer->kontak_person ?? '-',
            $customer->jabatan_kontak ?? '-',
            $customer->no_hp_kontak ?? '-',
            $customer->catatan ?? '-',
            $customer->is_active ? 'Aktif' : 'Non-Aktif',
            $formatDate($customer->created_at),
            $formatDate($customer->updated_at)
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => '1E3A8A'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Set height for header
                $sheet->getRowDimension(1)->setRowHeight(25);

                // Auto-size columns A to Y
                foreach (range('A', 'Z') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // Add borders to all data cells
                $sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'D1D5DB'],
                        ],
                    ],
                ]);

                // Alignments
                $sheet->getStyle('A1:A' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B1:B' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('D1:D' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('L1:L' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('W1:W' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('X1:Y' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
