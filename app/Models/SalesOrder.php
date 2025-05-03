<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory;
    
    protected $table = 'sales_order';
    
    protected $fillable = [
        'nomor',
        'tanggal',
        'customer_id',
        'quotation_id',
        'user_id',
        'subtotal',
        'diskon_persen',
        'diskon_nominal',
        'ppn',
        'total',
        'status_pembayaran',
        'status_pengiriman',
        'tanggal_kirim',
        'alamat_pengiriman',
        'catatan',
        'syarat_ketentuan'
    ];
    
    /**
     * Relasi ke Customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    
    /**
     * Relasi ke Quotation
     */
    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }
    
    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Relasi ke Detail Sales Order
     */
    public function details()
    {
        return $this->hasMany(SalesOrderDetail::class, 'sales_order_id');
    }
    
    /**
     * Relasi ke Work Order
     */
    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class, 'sales_order_id');
    }
    
    /**
     * Relasi ke Delivery Order
     */
    public function deliveryOrders()
    {
        return $this->hasMany(DeliveryOrder::class, 'sales_order_id');
    }
    
    /**
     * Relasi ke Invoice
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'sales_order_id');
    }
}