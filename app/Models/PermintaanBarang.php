<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanBarang extends Model
{
    use HasFactory;

    protected $table = 'permintaan_barang';

    protected $fillable = [
        'nomor',
        'tanggal',
        'sales_order_id',
        'customer_id',
        'user_id',
        'gudang_id',
        'status', // 'menunggu', 'diproses', 'selesai', 'dibatalkan'
        'catatan',
        'created_by',
        'updated_by'
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
     * Relasi ke Detail Permintaan Barang
     */
    public function details()
    {
        return $this->hasMany(PermintaanBarangDetail::class, 'permintaan_barang_id');
    }

    /**
     * Relasi ke Delivery Order
     */
    public function deliveryOrders()
    {
        return $this->hasMany(DeliveryOrder::class, 'permintaan_barang_id');
    }
}
