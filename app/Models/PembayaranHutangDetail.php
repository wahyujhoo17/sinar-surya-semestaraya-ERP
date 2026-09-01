<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranHutangDetail extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_hutang_detail';

    protected $fillable = [
        'pembayaran_hutang_id',
        'purchase_order_id',
        'jumlah',
        'catatan'
    ];

    protected $casts = [
        'jumlah' => 'decimal:2'
    ];

    /**
     * Relasi ke Pembayaran Hutang (Header)
     */
    public function pembayaranHutang(): BelongsTo
    {
        return $this->belongsTo(PembayaranHutang::class, 'pembayaran_hutang_id');
    }

    /**
     * Relasi ke Purchase Order
     */
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }
}
