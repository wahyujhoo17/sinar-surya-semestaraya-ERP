<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatStok extends Model
{
    use HasFactory;
    
    protected $table = 'riwayat_stok';
    
    protected $fillable = [
        'stok_id',
        'produk_id',
        'gudang_id',
        'user_id',
        'jumlah_sebelum',
        'jumlah_perubahan',
        'jumlah_setelah',
        'jenis', // 'masuk', 'keluar', 'penyesuaian', 'transfer'
        'referensi_tipe', // 'penjualan', 'pembelian', 'produksi', 'transfer', 'adjustment', dll
        'referensi_id', // ID dokumen terkait (ID PO, SO, dll)
        'keterangan'
    ];
    
    /**
     * Relasi ke Stok Produk
     */
    public function stok()
    {
        return $this->belongsTo(StokProduk::class, 'stok_id');
    }
    
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
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}