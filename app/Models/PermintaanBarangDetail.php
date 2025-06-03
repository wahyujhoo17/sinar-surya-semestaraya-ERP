<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanBarangDetail extends Model
{
    use HasFactory;

    protected $table = 'permintaan_barang_detail';

    protected $fillable = [
        'permintaan_barang_id',
        'produk_id',
        'sales_order_detail_id',
        'satuan_id',
        'jumlah',
        'jumlah_tersedia',
        'keterangan'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'jumlah' => 'decimal:2',
        'jumlah_tersedia' => 'decimal:2',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'jumlah' => 0,
        'jumlah_tersedia' => 0,
    ];

    /**
     * Relasi ke Permintaan Barang
     */
    public function permintaanBarang()
    {
        return $this->belongsTo(PermintaanBarang::class, 'permintaan_barang_id');
    }

    /**
     * Relasi ke Produk
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    /**
     * Relasi ke Sales Order Detail
     */
    public function salesOrderDetail()
    {
        return $this->belongsTo(SalesOrderDetail::class, 'sales_order_detail_id');
    }

    /**
     * Relasi ke Satuan
     */
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }
}
