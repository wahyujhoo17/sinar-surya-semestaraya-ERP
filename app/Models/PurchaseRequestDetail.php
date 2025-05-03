<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequestDetail extends Model
{
    use HasFactory;
    
    protected $table = 'purchase_request_detail';
    
    protected $fillable = [
        'pr_id',
        'produk_id',
        'nama_item',
        'deskripsi',
        'quantity',
        'satuan_id',
        'harga_estimasi'
    ];
    
    /**
     * Relasi ke Purchase Request
     */
    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class, 'pr_id');
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
}