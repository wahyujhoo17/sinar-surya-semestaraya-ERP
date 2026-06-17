<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;

    protected $table = 'invoice_detail';

    protected $fillable = [
        'invoice_id',
        'produk_id',
        'nama_produk',
        'deskripsi',
        'quantity',
        'qty',
        'satuan',
        'satuan_id',
        'harga',
        'diskon',
        'diskon_persen',
        'diskon_nominal',
        'item_type',
        'bundle_id',
        'is_bundle_item',
        'parent_detail_id',
        'subtotal'
    ];

    /**
     * Relasi ke Invoice
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    /**
     * Relasi ke Produk
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    /**
     * Relasi ke Satuan
     */
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    /**
     * Relasi ke Product Bundle
     */
    public function bundle()
    {
        return $this->belongsTo(ProductBundle::class, 'bundle_id');
    }

    /**
     * Alias untuk relasi Product Bundle
     */
    public function productBundle()
    {
        return $this->belongsTo(ProductBundle::class, 'bundle_id');
    }

    /**
     * Relasi ke parent detail (untuk bundle items)
     */
    public function parentDetail()
    {
        return $this->belongsTo(InvoiceDetail::class, 'parent_detail_id');
    }

    /**
     * Relasi ke child details (bundle breakdown)
     */
    public function childDetails()
    {
        return $this->hasMany(InvoiceDetail::class, 'parent_detail_id');
    }

    /**
     * Check if this is a bundle item
     */
    public function isBundleItem()
    {
        return $this->item_type === 'bundle';
    }

    /**
     * Check if this is part of bundle breakdown
     */
    public function isPartOfBundle()
    {
        return $this->is_bundle_item && $this->parent_detail_id !== null;
    }
}
