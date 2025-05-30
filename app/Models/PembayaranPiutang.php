<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranPiutang extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_piutang';

    protected $fillable = [
        'nomor',
        'tanggal',
        'invoice_id',
        'customer_id',
        'jumlah',
        'metode_pembayaran',
        'no_referensi',
        'catatan',
        'user_id',
        'kas_id',
        'rekening_bank_id'
    ];

    /**
     * Relasi ke Invoice
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
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
     * Relasi ke Kas
     */
    public function kas(): BelongsTo
    {
        return $this->belongsTo(Kas::class, 'kas_id');
    }

    /**
     * Relasi ke Rekening Bank
     */
    public function rekeningBank(): BelongsTo
    {
        return $this->belongsTo(RekeningBank::class, 'rekening_bank_id');
    }

    /**
     * Get the bank transaction associated with the payment.
     */
    public function transaksiBank(): MorphOne
    {
        return $this->morphOne(TransaksiBank::class, 'related');
    }

    /**
     * Get the cash transaction associated with the payment.
     */
    public function transaksiKas(): MorphOne
    {
        return $this->morphOne(TransaksiKas::class, 'related');
    }
}
