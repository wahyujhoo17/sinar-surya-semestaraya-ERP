<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $fillable = [
        'nama',
        'kode',
        'product_sku',
        'kategori_id',
        'jenis_id',
        'ukuran',
        'satuan_id',
        'merek',
        'sub_kategori',
        'type_material',
        'kualitas',
        'deskripsi',
        'harga_beli',
        'harga_jual',
        'stok_minimum',
        'is_active',
        'bahan',
        'gambar'
    ];

    /**
     * Relasi ke Kategori Produk
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class, 'kategori_id');
    }

    /**
     * Relasi ke Jenis Produk
     */
    public function jenis()
    {
        return $this->belongsTo(JenisProduk::class, 'jenis_id');
    }

    /**
     * Relasi ke Satuan
     */
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    /**
     * Relasi ke Stok di berbagai gudang
     */
    public function stok()
    {
        return $this->hasMany(StokProduk::class, 'produk_id');
    }

    /**
     * Relasi ke SupplierProduk
     */
    public function supplierProduks()
    {
        return $this->hasMany(SupplierProduk::class, 'produk_id');
    }

    /**
     * Mendapatkan total stok produk dari semua gudang
     */
    public function getTotalStokAttribute()
    {
        return $this->stok->sum('jumlah');
    }

    /**
     * Mendapatkan stok tersedia dari gudang tertentu atau total dari semua gudang
     */
    public function stokTersedia($gudangId = null)
    {
        if ($gudangId) {
            return $this->stok()
                ->where('gudang_id', $gudangId)
                ->value('jumlah') ?? 0;
        }

        return $this->stok()->sum('jumlah') ?? 0;
    }

    /**
     * Mendapatkan stok tersedia dari semua gudang (alias untuk stokTersedia tanpa parameter)
     */
    public function getTotalStokTersediaAttribute()
    {
        return $this->stokTersedia();
    }

    /**
     * Mendapatkan detail stok per gudang
     */
    public function getStokPerGudang()
    {
        return $this->stok()
            ->with('gudang')
            ->get()
            ->map(function ($stok) {
                return [
                    'gudang_id' => $stok->gudang_id,
                    'gudang_nama' => $stok->gudang->nama ?? 'Unknown',
                    'jumlah' => $stok->jumlah,
                    'lokasi_rak' => $stok->lokasi_rak,
                    'batch_number' => $stok->batch_number
                ];
            });
    }

    /**
     * Cek apakah stok mencukupi untuk quantity tertentu
     */
    public function isStokCukup($quantity, $gudangId = null)
    {
        return $this->stokTersedia($gudangId) >= $quantity;
    }
}
