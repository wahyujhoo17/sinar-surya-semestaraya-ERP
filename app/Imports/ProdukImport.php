<?php

namespace App\Imports;

use App\Models\Produk;
use App\Models\KategoriProduk;
use App\Models\Satuan;
use App\Models\JenisProduk;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ProdukImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                // Generate kode produk otomatis jika kosong
                $kodeProduk = trim($row['kode']);
                if (empty($kodeProduk)) {
                    $lastProduk = Produk::orderBy('id', 'desc')->first();
                    $lastNumber = $lastProduk ? intval(substr($lastProduk->kode, 3)) : 0;
                    $kodeProduk = 'PRD' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
                }

                // Validasi kode terlebih dahulu
                if (Produk::where('kode', $kodeProduk)->exists()) {
                    throw new \Exception("Kode produk '{$kodeProduk}' sudah digunakan");
                }

                // Cari atau buat kategori
                $kategoriNama = trim($row['kategori']);
                $kategori = KategoriProduk::where('nama', $kategoriNama)->first();
                if (!$kategori) {
                    // Generate kode kategori otomatis
                    $lastKategori = KategoriProduk::orderBy('id', 'desc')->first();
                    $lastNumber = $lastKategori ? intval(substr($lastKategori->kode, 3)) : 0;
                    $newKode = 'KTG' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

                    $kategori = KategoriProduk::create([
                        'nama' => $kategoriNama,
                        'kode' => $newKode,
                        'is_active' => true
                    ]);
                }

                // Cari atau buat satuan
                $satuanNama = trim($row['satuan']);
                $satuan = Satuan::where('nama', $satuanNama)->first();
                if (!$satuan) {
                    // Generate kode satuan otomatis
                    $lastSatuan = Satuan::orderBy('id', 'desc')->first();
                    $lastNumber = $lastSatuan ? intval(substr($lastSatuan->kode, 3)) : 0;
                    $newKode = 'STN' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

                    $satuan = Satuan::create([
                        'nama' => $satuanNama,
                        'kode' => $newKode,
                        'is_active' => true
                    ]);
                }

                // Cari atau buat jenis
                $jenisNama = trim($row['jenis']);
                $jenis = JenisProduk::where('nama', $jenisNama)->first();
                if (!$jenis) {
                    // Generate kode jenis otomatis
                    $lastJenis = JenisProduk::orderBy('id', 'desc')->first();
                    $lastNumber = $lastJenis ? intval(substr($lastJenis->kode, 3)) : 0;
                    $newKode = 'JNS' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

                    $jenis = JenisProduk::create([
                        'nama' => $jenisNama,
                        'kode' => $newKode,
                        'is_active' => true
                    ]);
                }

                // Create produk with proper field mapping
                $produk = Produk::create([
                    'kode' => $kodeProduk,
                    'nama' => trim($row['nama']),
                    'product_sku' => !empty($row['sku']) ? trim($row['sku']) : null,
                    'kategori_id' => $kategori->id,
                    'jenis_id' => $jenis->id,
                    'merek' => !empty($row['merek']) ? trim($row['merek']) : null,
                    'sub_kategori' => !empty($row['sub_kategori']) ? trim($row['sub_kategori']) : null,
                    'satuan_id' => $satuan->id,
                    'ukuran' => !empty($row['ukuran']) ? trim($row['ukuran']) : null,
                    'type_material' => !empty($row['tipe_material']) ? trim($row['tipe_material']) : null,
                    'kualitas' => !empty($row['kualitas']) ? trim($row['kualitas']) : null,
                    'stok_minimum' => !empty($row['stok_minimum']) ? floatval($row['stok_minimum']) : 0,
                    'is_active' => true,
                    'deskripsi' => null,
                    'gambar' => null
                ]);

                if (!$produk) {
                    throw new \Exception("Gagal membuat produk untuk baris: " . json_encode($row));
                }
            } catch (\Exception $e) {
                Log::error('Import error for row: ' . json_encode($row));
                Log::error('Error message: ' . $e->getMessage());
                throw new \Exception('Error pada baris dengan kode: ' . ($row['kode'] ?? 'unknown') . '. ' . $e->getMessage());
            }
        }
    }

    public function rules(): array
    {
        return [
            '*.kode' => 'nullable|unique:produk,kode|max:50',
            '*.nama' => 'required|max:255',
            '*.sku' => 'nullable|max:100',
            '*.kategori' => 'required',
            '*.merek' => 'nullable|max:100',
            '*.sub_kategori' => 'nullable|max:100',
            '*.satuan' => 'required',
            '*.ukuran' => 'nullable|max:50',
            '*.tipe_material' => 'nullable|max:100',
            '*.kualitas' => 'nullable|max:100',
            '*.stok_minimum' => 'nullable|numeric|min:0',
            '*.status' => 'nullable|in:Aktif,Nonaktif',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.kode.unique' => 'Kode produk sudah digunakan',
            '*.nama.required' => 'Kolom nama wajib diisi',
            '*.kategori.required' => 'Kolom kategori wajib diisi',
            '*.satuan.required' => 'Kolom satuan wajib diisi',
            '*.harga_beli.required' => 'Kolom harga beli wajib diisi',
            '*.harga_jual.required' => 'Kolom harga jual wajib diisi',
            '*.status.required' => 'Kolom status wajib diisi',
            '*.status.in' => 'Status harus Aktif atau Nonaktif',
        ];
    }
}