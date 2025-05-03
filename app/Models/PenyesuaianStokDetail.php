<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenyesuaianStokDetail extends Model
{
    use HasFactory;
    
    protected $table = 'penyesuaian_stok_detail';
    
    protected $fillable = [
        'penyesuaian_id',
        'produk_id',
        'stok_tercatat',
        'stok_fisik',
        'selisih',
        'satuan_id',
        'keterangan'
    ];
    
    /**
     * Relasi ke Penyesuaian Stok
     */
    public function penyesuaian()
    {
        return $this->belongsTo(PenyesuaianStok::class, 'penyesuaian_id');
    }
    
    /**
     * Relasi ke Produk
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
    
    /**
     * Relasi ke Satuan
     */
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }
}