<?php

namespace App\Exports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SupplierExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Supplier::all();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Kode',
            'Nama',
            'Alamat',
            'Telepon',
            'Email',
            'Nama Kontak',
            'No. HP',
            'NPWP',
            'Tipe Produksi',
            'Catatan',
            'Status',
        ];
    }

    /**
     * @param mixed $supplier
     * @return array
     */
    public function map($supplier): array
    {
        return [
            $supplier->kode,
            $supplier->nama,
            $supplier->alamat,
            $supplier->telepon,
            $supplier->email,
            $supplier->nama_kontak,
            $supplier->no_hp,
            $supplier->NPWP,
            $supplier->type_produksi,
            $supplier->catatan,
            $supplier->is_active ? 'Aktif' : 'Nonaktif',
        ];
    }
}
