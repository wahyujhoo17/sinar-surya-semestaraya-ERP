<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'purchase_order';

    protected $fillable = [
        'nomor',
        'tanggal',
        'supplier_id',
        'user_id',
        'total',
        'status_pembayaran',
        'catatan'
    ];

    /**
     * Get the supplier associated with the purchase
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the user who created the purchase
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the purchase details
     */
    public function detail()
    {
        return $this->hasMany(PembelianDetail::class, 'po_id');
    }

    /**
     * Accessor for nomor_faktur to alias the nomor field
     */
    public function getNomorFakturAttribute()
    {
        return $this->nomor;
    }

    /**
     * Accessor for total_bayar to get payment total
     */
    public function getTotalBayarAttribute()
    {
        return $this->pembayaran()->sum('jumlah') ?? 0;
    }

    /**
     * Relation to payments
     */
    public function pembayaran()
    {
        return $this->hasMany(PembayaranHutang::class, 'purchase_order_id');
    }
}
