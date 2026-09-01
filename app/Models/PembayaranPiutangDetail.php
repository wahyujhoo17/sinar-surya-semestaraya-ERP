<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranPiutangDetail extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_piutang_detail';

    protected $fillable = [
        'pembayaran_piutang_id',
        'invoice_id',
        'jumlah',
        'catatan'
    ];

    protected $casts = [
        'jumlah' => 'decimal:2'
    ];

    /**
     * Relasi ke Pembayaran Piutang (Header)
     */
    public function pembayaranPiutang(): BelongsTo
    {
        return $this->belongsTo(PembayaranPiutang::class, 'pembayaran_piutang_id');
    }

    /**
     * Relasi ke Invoice
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
