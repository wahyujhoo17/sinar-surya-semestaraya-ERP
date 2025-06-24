<?php

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomerExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    private $salesId;

    public function __construct($salesId = null)
    {
        $this->salesId = $salesId;
    }

    /**
     * Check if current user can access all customers (admin/manager_penjualan)
     */
    private function canAccessAllCustomers()
    {
        return Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager_penjualan');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if ($this->canAccessAllCustomers()) {
            return Customer::all();
        } else {
            return Customer::where('sales_id', Auth::id())->get();
        }
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Kode',
            'Nama',
            'Tipe',
            'Jalan',
            'Kota',
            'Provinsi',
            'Kode Pos',
            'Negara',
            'Company',
            'Group',
            'Industri',
            'Sales',
            'Alamat',
            'Alamat Pengiriman',
            'Telepon',
            'Email',
            'NPWP',
            'Kontak Person',
            'No HP Kontak',
            'Catatan',
            'Status',
            'Tanggal Dibuat',
            'Tanggal Update'
        ];
    }

    /**
     * @param Customer $customer
     * @return array
     */
    public function map($customer): array
    {
        return [
            $customer->kode,
            $customer->nama,
            $customer->tipe,
            $customer->jalan,
            $customer->kota,
            $customer->provinsi,
            $customer->kode_pos,
            $customer->negara,
            $customer->company,
            $customer->group,
            $customer->industri,
            $customer->sales_name,
            $customer->alamat,
            $customer->alamat_pengiriman,
            $customer->telepon,
            $customer->email,
            $customer->npwp,
            $customer->kontak_person,
            $customer->no_hp_kontak,
            $customer->catatan,
            $customer->is_active ? 'Aktif' : 'Non Aktif',
            $customer->created_at->format('d/m/Y H:i'),
            $customer->updated_at->format('d/m/Y H:i')
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],
        ];
    }
}
