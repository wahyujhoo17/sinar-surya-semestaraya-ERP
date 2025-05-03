<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetail extends Model
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
     * Relasi ke Purchase Order
     */
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
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
     * Relasi ke Penerimaan Barang Detail
     */
    public function penerimaanDetail()
    {
        return $this->hasMany(PenerimaanBarangDetail::class, 'po_detail_id');
    }
}