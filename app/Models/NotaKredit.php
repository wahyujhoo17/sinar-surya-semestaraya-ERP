<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaKredit extends Model
{
    use HasFactory;

    protected $table = 'nota_kredit';

    protected $fillable = [
        'nomor',
        'tanggal',
        'retur_penjualan_id',
        'customer_id',
        'sales_order_id',
        'user_id',
        'total',
        'status',
        'catatan'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Relasi ke Retur Penjualan
     */
    public function returPenjualan()
    {
        return $this->belongsTo(ReturPenjualan::class, 'retur_penjualan_id');
    }

    /**
     * Relasi ke Customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Relasi ke Sales Order
     */
    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Detail Nota Kredit
     */
    public function details()
    {
        return $this->hasMany(NotaKreditDetail::class, 'nota_kredit_id');
    }

    /**
     * Relasi ke Invoice (yang telah menerima kredit dari nota kredit ini)
     */
    public function invoices()
    {
        return $this->belongsToMany(Invoice::class, 'nota_kredit_invoice')
            ->withPivot('applied_amount')
            ->withTimestamps();
    }
}
