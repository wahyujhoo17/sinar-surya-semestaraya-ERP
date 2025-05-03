<?php

namespace App\Imports;

use App\Models\Customer;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {

        // Generate kode jika kosong
        $kode = $row['kode'] ?? null;
        if (empty($kode)) {
            $prefix = 'CUST';
            $last = Customer::orderByDesc('id')->first();
            $lastNumber = 0;
            if ($last && preg_match('/^CUST(\d+)$/', $last->kode, $matches)) {
                $lastNumber = (int)$matches[1];
            }
            $newNumber = $lastNumber + 1;
            $kode = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        }

        return new Customer([
            'kode'          => $kode,
            'nama'          => $row['nama'] ?? null,
            'tipe'          => $row['tipe'] ?? null,
            'jalan'         => $row['jalan'] ?? null,
            'kota'          => $row['kota'] ?? null,
            'provinsi'      => $row['provinsi'] ?? null,
            'kode_pos'      => $row['kode_pos'] ?? null,
            'negara'        => $row['negara'] ?? null,
            'company'       => $row['company'] ?? null,
            'group'         => $row['group'] ?? null,
            'industri'      => $row['industri'] ?? null,
            'sales_name'    => $row['sales_name'] ?? null,
            'alamat'        => $row['alamat'] ?? null,
            'telepon'       => $row['telepon'] ?? null,
            'email'         => $row['email'] ?? null,
            'catatan'       => $row['catatan'] ?? null,
            'is_active'     => isset($row['is_active']) ? (bool)$row['is_active'] : true,
        ]);
    }
}
