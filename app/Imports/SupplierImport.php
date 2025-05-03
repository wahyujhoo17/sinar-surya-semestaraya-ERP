<?php

namespace App\Imports;

use App\Models\Supplier;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SupplierImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Lewati jika nama kosong
        if (empty($row['nama'])) {
            return null;
        }

        // Generate kode jika kosong
        $kode = $row['kode'] ?? null;
        if (empty($kode)) {
            $lastSupplier = Supplier::orderByDesc('id')->first();
            $lastNumber = 1;
            if ($lastSupplier && preg_match('/SUP-(\d+)/', $lastSupplier->kode, $matches)) {
                $lastNumber = intval($matches[1]) + 1;
            } elseif ($lastSupplier) {
                $lastNumber = $lastSupplier->id + 1;
            }
            $kode = 'SUP-' . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);
        }

        return new Supplier([
            'kode'          => $kode,
            'nama'          => $row['nama'],
            'alamat'        => $row['alamat'] ?? null,
            'telepon'       => $row['telepon'] ?? null,
            'email'         => $row['email'] ?? null,
            'type_produksi' => $row['tipe_produksi'] ?? null,
            'catatan'       => $row['catatan'] ?? null,
            'is_active'     => isset($row['aktif']) ? (bool)$row['aktif'] : true,
        ]);
    }
}