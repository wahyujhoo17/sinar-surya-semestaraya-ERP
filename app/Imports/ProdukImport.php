<?php

namespace App\Imports;

use App\Models\Produk;
use App\Models\KategoriProduk;
use App\Models\Satuan;
use App\Models\JenisProduk;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ProdukImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public $stats = [
        'inserted' => 0,
        'updated' => 0,
        'skipped_duplicate' => 0,
        'skipped_empty' => 0,
    ];

    protected $processedCodes = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Normalize keys - hapus * dan whitespace, lowercase
            $normalizedRow = [];
            foreach ($row as $key => $value) {
                $cleanKey = strtolower(trim(str_replace('*', '', $key)));
                $normalizedRow[$cleanKey] = $value;
            }
            $row = $normalizedRow;

            try {
                // Skip baris kosong
                if (empty($row['nama']) && empty($row['kode'])) {
                    $this->stats['skipped_empty']++;
                    continue;
                }

                // Validasi manual - nama wajib diisi
                if (empty($row['nama'])) {
                    Log::warning('Produk Import: Nama kosong', ['row' => $row]);
                    throw new \Exception('Nama produk wajib diisi');
                }

                // Generate kode produk otomatis jika kosong
                $kodeProduk = !empty($row['kode']) ? trim($row['kode']) : '';
                if (empty($kodeProduk)) {
                    $lastProduk = Produk::orderBy('id', 'desc')->first();
                    $lastNumber = $lastProduk ? intval(substr($lastProduk->kode, 3)) : 0;
                    $kodeProduk = 'PRD' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
                }

                // Cek duplicate dalam batch import
                if (in_array($kodeProduk, $this->processedCodes)) {
                    $this->stats['skipped_duplicate']++;
                    Log::warning('Produk Import: Duplicate kode in file', ['kode' => $kodeProduk]);
                    continue;
                }
                $this->processedCodes[] = $kodeProduk;

                // Cek apakah produk sudah ada - update jika ada
                $existingProduk = Produk::where('kode', $kodeProduk)->first();

                // Cari atau buat kategori jika ada
                $kategoriId = null;
                if (!empty($row['kategori'])) {
                    $kategoriNama = trim($row['kategori']);
                    $kategori = KategoriProduk::where('nama', $kategoriNama)->first();
                    if (!$kategori) {
                        $lastKategori = KategoriProduk::orderBy('id', 'desc')->first();
                        $lastNumber = $lastKategori ? intval(substr($lastKategori->kode, 3)) : 0;
                        $newKode = 'KTG' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

                        $kategori = KategoriProduk::create([
                            'nama' => $kategoriNama,
                            'kode' => $newKode,
                            'is_active' => true
                        ]);
                    }
                    $kategoriId = $kategori->id;
                }

                // Cari atau buat satuan jika ada
                $satuanId = null;
                if (!empty($row['satuan'])) {
                    $satuanNama = trim($row['satuan']);
                    $satuan = Satuan::where('nama', $satuanNama)->first();
                    if (!$satuan) {
                        $lastSatuan = Satuan::orderBy('id', 'desc')->first();
                        $lastNumber = $lastSatuan ? intval(substr($lastSatuan->kode, 3)) : 0;
                        $newKode = 'STN' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

                        $satuan = Satuan::create([
                            'nama' => $satuanNama,
                            'kode' => $newKode,
                            'is_active' => true
                        ]);
                    }
                    $satuanId = $satuan->id;
                }

                // Cari atau buat jenis jika ada
                $jenisId = null;
                if (!empty($row['jenis'])) {
                    $jenisNama = trim($row['jenis']);
                    $jenis = JenisProduk::where('nama', $jenisNama)->first();
                    if (!$jenis) {
                        $lastJenis = JenisProduk::orderBy('id', 'desc')->first();
                        $lastNumber = $lastJenis ? intval(substr($lastJenis->kode, 3)) : 0;
                        $newKode = 'JNS' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

                        $jenis = JenisProduk::create([
                            'nama' => $jenisNama,
                            'kode' => $newKode,
                            'is_active' => true
                        ]);
                    }
                    $jenisId = $jenis->id;
                }

                // Prepare data - support berbagai format nama kolom
                $produkData = [
                    'kode' => $kodeProduk,
                    'nama' => !empty($row['nama']) ? trim($row['nama']) : null,
                    'product_sku' => !empty($row['sku']) ? trim($row['sku']) : null,
                    'kategori_id' => $kategoriId,
                    'jenis_id' => $jenisId,
                    'merek' => !empty($row['merek']) ? trim($row['merek']) : null,
                    'sub_kategori' => !empty($row['sub kategori']) ? trim($row['sub kategori']) : (!empty($row['sub_kategori']) ? trim($row['sub_kategori']) : null),
                    'satuan_id' => $satuanId,
                    'ukuran' => !empty($row['ukuran']) ? trim($row['ukuran']) : null,
                    'type_material' => !empty($row['tipe material']) ? trim($row['tipe material']) : (!empty($row['tipe_material']) ? trim($row['tipe_material']) : null),
                    'kualitas' => !empty($row['kualitas']) ? trim($row['kualitas']) : null,
                    'harga_beli' => !empty($row['harga beli']) ? floatval($row['harga beli']) : (!empty($row['harga_beli']) ? floatval($row['harga_beli']) : null),
                    'harga_jual' => !empty($row['harga jual']) ? floatval($row['harga jual']) : (!empty($row['harga_jual']) ? floatval($row['harga_jual']) : null),
                    'stok_minimum' => !empty($row['stok minimum']) ? floatval($row['stok minimum']) : (!empty($row['stok_minimum']) ? floatval($row['stok_minimum']) : 0),
                    'is_active' => !empty($row['status']) ? (strtolower(trim($row['status'])) === 'aktif') : true,
                    'deskripsi' => null,
                    'gambar' => null
                ];

                if ($existingProduk) {
                    $existingProduk->update($produkData);
                    $this->stats['updated']++;
                    Log::info('Produk Import: Updated existing produk', ['kode' => $kodeProduk]);
                } else {
                    Produk::create($produkData);
                    $this->stats['inserted']++;
                }
            } catch (\Exception $e) {
                Log::error('Import error for row: ' . json_encode($row));
                Log::error('Error message: ' . $e->getMessage());
                throw new \Exception('Error pada baris dengan kode: ' . ($row['kode'] ?? 'unknown') . '. ' . $e->getMessage());
            }
        }
    }

    public function getStats()
    {
        return $this->stats;
    }
}
