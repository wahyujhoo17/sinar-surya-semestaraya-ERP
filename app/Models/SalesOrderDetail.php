<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderDetail extends Model
{
    use HasFactory;
    
    protected $table = 'sales_order_detail';
    
    protected $fillable = [
        'sales_order_id',
        'produk_id',
        'deskripsi',
        'quantity',
        'quantity_terkirim',
        'satuan_id',
        'harga',
        'diskon_persen',
        'diskon_nominal',
        'subtotal'
    ];
    
    /**
     * Relasi ke Sales Order
     */
    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
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