<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengembalianDana extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model ini
     *
     * @var string
     */
    protected $table = 'pengembalian_dana';

    /**
     * Atribut yang dapat diisi secara massal
     *
     * @var array
     */
    protected $fillable = [
        'nomor',
        'tanggal',
        'purchase_order_id',
        'supplier_id',
        'metode_penerimaan',
        'kas_id',
        'rekening_id',
        'no_referensi',
        'jumlah',
        'catatan',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'float',
    ];

    /**
     * Get the purchase order that owns the PengembalianDana
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    /**
     * Get the supplier that owns the PengembalianDana
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /**
     * Get the kas that owns the PengembalianDana
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kas(): BelongsTo
    {
        return $this->belongsTo(Kas::class, 'kas_id');
    }

    /**
     * Get the rekening bank that owns the PengembalianDana
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rekeningBank(): BelongsTo
    {
        return $this->belongsTo(RekeningBank::class, 'rekening_id');
    }

    /**
     * Get the user that created the PengembalianDana
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
