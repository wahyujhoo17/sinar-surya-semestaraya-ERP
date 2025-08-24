<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBundleItem extends Model
{
    use HasFactory;

    protected $table = 'product_bundle_items';

    protected $fillable = [
        'bundle_id',
        'produk_id',
        'quantity',
        'harga_satuan'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'harga_satuan' => 'decimal:2'
    ];

    /**
     * Relasi ke product bundle
     */
    public function bundle()
    {
        return $this->belongsTo(ProductBundle::class, 'bundle_id');
    }

    /**
     * Relasi ke produk
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    /**
     * Hitung subtotal item dalam bundle
     */
    public function getSubtotalAttribute()
    {
        $harga = $this->harga_satuan ?? $this->produk->harga_jual;
        return $harga * $this->quantity;
    }
}
