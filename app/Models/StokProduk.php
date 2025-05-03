<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokProduk extends Model
{
    use HasFactory;
    
    protected $table = 'stok_produk';
    
    protected $fillable = [
        'produk_id',
        'gudang_id',
        'jumlah',
        'lokasi_rak', // informasi rak/lokasi fisik di gudang
        'batch_number' // nomor batch produksi jika ada
    ];
    
    /**
     * Relasi ke Produk
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
    
    /**
     * Relasi ke Gudang
     */
    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }
    
    /**
     * Relasi ke Riwayat Stok
     */
    public function riwayat()
    {
        return $this->hasMany(RiwayatStok::class, 'stok_id');
    }
}