<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;
    
    protected $table = 'quotation';
    
    protected $fillable = [
        'nomor',
        'tanggal',
        'customer_id',
        'user_id',
        'subtotal',
        'diskon_persen',
        'diskon_nominal',
        'ppn',
        'total',
        'status', // 'draft', 'diajukan', 'disetujui', 'ditolak', 'expired', 'diterima'
        'tanggal_berlaku', // masa berlaku penawaran
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
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Relasi ke Detail Quotation
     */
    public function details()
    {
        return $this->hasMany(QuotationDetail::class, 'quotation_id');
    }
    
    /**
     * Relasi ke Sales Order
     */
    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class, 'quotation_id');
    }
}