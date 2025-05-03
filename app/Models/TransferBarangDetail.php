<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferBarangDetail extends Model
{
    use HasFactory;
    
    protected $table = 'transfer_barang_detail';
    
    protected $fillable = [
        'transfer_id',
        'produk_id',
        'quantity',
        'satuan_id',
        'keterangan'
    ];
    
    /**
     * Relasi ke Transfer Barang
     */
    public function transferBarang()
    {
        return $this->belongsTo(TransferBarang::class, 'transfer_id');
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