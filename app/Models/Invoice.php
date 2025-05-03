<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    
    protected $table = 'invoice';
    
    protected $fillable = [
        'nomor',
        'tanggal',
        'sales_order_id',
        'customer_id',
        'user_id',
        'subtotal',
        'diskon_persen',
        'diskon_nominal',
        'ppn',
        'total',
        'jatuh_tempo',
        'status', // 'belum_bayar', 'sebagian', 'lunas'
        'catatan',
        'syarat_ketentuan'
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
     * Relasi ke Detail Invoice
     */
    public function details()
    {
        return $this->hasMany(InvoiceDetail::class, 'invoice_id');
    }
    
    /**
     * Relasi ke Pembayaran
     */
    public function pembayaran()
    {
        return $this->hasMany(PembayaranPiutang::class, 'invoice_id');
    }
}