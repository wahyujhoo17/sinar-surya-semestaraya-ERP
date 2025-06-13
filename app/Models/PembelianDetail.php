<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianDetail extends Model
{
    use HasFactory;

    protected $table = 'purchase_order_detail';

    protected $fillable = [
        'po_id',
        'produk_id',
        'nama_item',
        'deskripsi',
        'quantity',
        'quantity_diterima',
        'satuan_id',
        'harga',
        'diskon_persen',
        'diskon_nominal',
        'subtotal'
    ];

    /**
     * Get the purchase this detail belongs to
     */
    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'po_id');
    }

    /**
     * Get the purchase order this detail belongs to
     */
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }

    /**
     * Get the product associated with this detail
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    /**
     * Get the unit associated with this detail
     */
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    /**
     * Accessor for jumlah to alias the quantity field
     */
    public function getJumlahAttribute()
    {
        return $this->quantity;
    }
}
