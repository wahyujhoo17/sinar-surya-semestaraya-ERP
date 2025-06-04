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
        'nomor_po',
        'tanggal',
        'customer_id',
        'quotation_id',
        'user_id',
        'subtotal',
        'diskon_persen',
        'diskon_nominal',
        'ppn',
        'total',
        'ongkos_kirim',
        'total_pembayaran',
        'kelebihan_bayar',
        'status_pembayaran',
        'status_pengiriman',
        'tanggal_kirim',
        'alamat_pengiriman',
        'catatan',
        'syarat_ketentuan',
        'terms_pembayaran',
        'terms_pembayaran_hari'
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

    /**
     * Relasi ke Retur Penjualan
     */
    public function returPenjualan()
    {
        return $this->hasMany(ReturPenjualan::class, 'sales_order_id');
    }

    /**
     * Relasi ke Log Aktivitas
     */
    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'data_id')->where('modul', 'sales_order');
    }
}
