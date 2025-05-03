<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturPembelianDetail extends Model
{
    use HasFactory;
    
    protected $table = 'retur_pembelian_detail';
    
    protected $fillable = [
        'retur_id',
        'produk_id',
        'quantity',
        'satuan_id',
        'alasan',
        'keterangan'
    ];
    
    /**
     * Relasi ke Retur Pembelian
     */
    public function returPembelian()
    {
        return $this->belongsTo(ReturPembelian::class, 'retur_id');
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