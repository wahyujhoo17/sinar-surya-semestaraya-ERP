<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryOrder extends Model
{
    use HasFactory;
    
    protected $table = 'delivery_order';
    
    protected $fillable = [
        'nomor',
        'tanggal',
        'sales_order_id',
        'customer_id',
        'user_id',
        'gudang_id',
        'alamat_pengiriman',
        'status', // 'draft', 'dikirim', 'diterima', 'dibatalkan'
        'catatan',
        'keterangan_penerima',
        'nama_penerima',
        'tanggal_diterima'
    ];
    
    /**
     * Relasi ke Sales Order
     */
    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }
    
    /**
     * Relasi ke Customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    
    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Relasi ke Gudang
     */
    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }
    
    /**
     * Relasi ke Detail Delivery Order
     */
    public function details()
    {
        return $this->hasMany(DeliveryOrderDetail::class, 'delivery_id');
    }
}