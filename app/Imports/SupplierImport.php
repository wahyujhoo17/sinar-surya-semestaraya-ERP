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

        // Helper function to get value checking multiple possible keys (case-insensitive keys from row)
        // WithHeadingRow usually converts to snake_case, so 'no_hp' is the primary key.
        // We check 'no. hp' as a fallback.
        $noHp = $row['no_hp'] ?? $row['no. hp'] ?? null;

        return new Supplier([
            'kode'          => $kode,
            'nama'          => $row['nama'] ?? null,
            'alamat'        => $row['alamat'] ?? null,
            'telepon'       => $row['telepon'] ?? null,
            'nama_kontak'   => $row['nama_kontak'] ?? null,
            'email'         => $row['email'] ?? null,
            'type_produksi' => $row['tipe_produksi'] ?? $row['type_produksi'] ?? null, // Also check type_produksi as fallback
            'catatan'       => $row['catatan'] ?? null,
            'is_active'     => isset($row['aktif']) ? filter_var($row['aktif'], FILTER_VALIDATE_BOOLEAN) : true, // Use filter_var for boolean
            'no_hp'         => $noHp, // Use the variable that checked multiple keys
        ]);
    }

    public function rules(): array
    {
        return [
            'kode'          => 'required|unique:supplier,kode|max:50',
            'nama'          => 'nullable|max:255',
            'alamat'        => 'nullable|string',
            'telepon'       => 'nullable|string|max:20',
            'nama_kontak'   => 'nullable|string|max:255',
            'email'         => 'nullable|email|max:255',
            'no_hp'         => 'nullable|string|max:20',
            'tipe_produksi' => 'nullable|string|max:100', // Match the key used in model()
            'catatan'       => 'nullable|string',
            'aktif'         => 'nullable|boolean',
        ];
    }

    public function chunkSize(): int
    {
        return 1000; // Process 1000 rows at a time
    }
}
