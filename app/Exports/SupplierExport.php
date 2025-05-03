<?php

namespace App\Exports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SupplierExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Supplier::select('kode', 'nama', 'alamat', 'telepon', 'email', 'type_produksi', 'catatan', 'is_active')->get();
    }

    public function headings(): array
    {
        return [
            'Kode', 'Nama', 'Alamat', 'Telepon', 'Email', 'Tipe Produksi', 'Catatan', 'Aktif'
        ];
    }
}