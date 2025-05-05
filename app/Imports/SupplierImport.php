<?php

namespace App\Imports;

use App\Models\Supplier;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class SupplierImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading
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
            'no_hp'         => $row['no_hp'] ?? null, // Add no_hp
        ]);
    }

    public function rules(): array
    {
        return [
            'kode'          => 'required|unique:supplier,kode|max:50',
            'nama'          => 'required|max:255',
            'alamat'        => 'nullable|string',
            'telepon'       => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:255',
            'no_hp'         => 'nullable|string|max:20', // Add validation for no_hp
            'type_produksi' => 'nullable|string|max:100',
            'catatan'       => 'nullable|string',
            'aktif'         => 'nullable|boolean',
        ];
    }

    public function chunkSize(): int
    {
        return 1000; // Process 1000 rows at a time
    }
}
