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
     * Mendapatkan total stok produk dari semua gudang
     */
    public function getTotalStokAttribute()
    {
        return $this->stok->sum('jumlah');
    }
}
