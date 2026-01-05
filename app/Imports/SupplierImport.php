<?php

namespace App\Imports;

use App\Models\Supplier;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\Importable;

class SupplierImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, SkipsEmptyRows
{
    use Importable;

    public $failures = [];
    protected $processedCodes = [];
    public $stats = [
        'inserted' => 0,
        'updated' => 0,
        'skipped_duplicate' => 0,
        'skipped_empty' => 0,
    ];

    public function model(array $row)
    {
        // Skip baris kosong - cek jika semua field penting kosong
        if (empty($row['nama']) && empty($row['kode']) && empty($row['alamat']) && empty($row['telepon'])) {
            $this->stats['skipped_empty']++;
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

        // Cek apakah kode sudah diproses dalam batch import ini
        if (in_array($kode, $this->processedCodes)) {
            $this->stats['skipped_duplicate']++;
            Log::warning('Supplier Import: Duplicate kode in file', ['kode' => $kode]);
            return null; // Skip duplicate dalam file yang sama
        }

        // Tambahkan ke daftar kode yang sudah diproses
        $this->processedCodes[] = $kode;

        $noHp = $row['no_hp'] ?? $row['no. hp'] ?? null;

        // Cek apakah supplier dengan kode ini sudah ada
        $existingSupplier = Supplier::where('kode', $kode)->first();

        $supplierData = [
            'kode'          => $kode,
            'nama'          => $row['nama'] ?? null,
            'alamat'        => $row['alamat'] ?? null,
            'telepon'       => $row['telepon'] ?? null,
            'nama_kontak'   => $row['nama_kontak'] ?? null,
            'email'         => $row['email'] ?? null,
            'type_produksi' => $row['tipe_produksi'] ?? $row['type_produksi'] ?? null,
            'catatan'       => $row['catatan'] ?? null,
            'is_active'     => isset($row['aktif']) ? filter_var($row['aktif'], FILTER_VALIDATE_BOOLEAN) : true,
            'no_hp'         => $noHp,
            'NPWP'          => $row['npwp'] ?? null,
        ];

        if ($existingSupplier) {
            // Update supplier yang sudah ada
            $existingSupplier->update($supplierData);
            $this->stats['updated']++;
            Log::info('Supplier Import: Updated existing supplier', ['kode' => $kode]);
            return null; // Return null karena sudah di-update manual
        }

        $this->stats['inserted']++;
        return new Supplier($supplierData);
    }

    public function rules(): array
    {
        return [
            'nama'          => 'required|max:255',
            'alamat'        => 'nullable|string',
            'nama_kontak'   => 'nullable|string|max:255',
            'tipe_produksi' => 'nullable|string|max:100',
            'type_produksi' => 'nullable|string|max:100',
            'catatan'       => 'nullable|string',
            'aktif'         => 'nullable|boolean',
            'email'         => 'nullable|email|max:255',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama.required' => 'Nama supplier wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ];
    }

    public function getFailures()
    {
        return $this->failures;
    }

    public function getStats()
    {
        return $this->stats;
    }

    public function chunkSize(): int
    {
        return 500; // Process 500 rows at a time
    }
}
