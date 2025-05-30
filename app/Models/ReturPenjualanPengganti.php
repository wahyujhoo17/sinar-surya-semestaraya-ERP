<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReturPenjualanPengganti extends Model
{
    use HasFactory;

    protected $table = 'retur_penjualan_pengganti';

    protected $fillable = [
        'retur_id',
        'produk_id',
        'gudang_id',
        'satuan_id',
        'quantity',
        'tanggal_pengiriman',
        'no_referensi',
        'catatan',
        'user_id'
    ];

    protected $casts = [
        'tanggal_pengiriman' => 'date',
        'quantity' => 'decimal:2'
    ];

    /**
     * Relasi ke Retur Penjualan
     */
    public function returPenjualan()
    {
        return $this->belongsTo(ReturPenjualan::class, 'retur_id');
    }

    /**
     * Relasi ke Produk
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    /**
     * Relasi ke Gudang
     */
    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }

    /**
     * Relasi ke Satuan
     */
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
