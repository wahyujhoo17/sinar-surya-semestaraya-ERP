<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryOrderDetail extends Model
{
    use HasFactory;
    
    protected $table = 'delivery_order_detail';
    
    protected $fillable = [
        'delivery_id',
        'sales_order_detail_id',
        'produk_id',
        'quantity',
        'satuan_id',
        'keterangan'
    ];
    
    /**
     * Relasi ke Delivery Order
     */
    public function deliveryOrder()
    {
        return $this->belongsTo(DeliveryOrder::class, 'delivery_id');
    }
    
    /**
     * Relasi ke Sales Order Detail
     */
    public function salesOrderDetail()
    {
        return $this->belongsTo(SalesOrderDetail::class, 'sales_order_detail_id');
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