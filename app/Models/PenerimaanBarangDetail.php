<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanBarangDetail extends Model
{
    use HasFactory;
    
    protected $table = 'penerimaan_barang_detail';
    
    protected $fillable = [
        'penerimaan_id',
        'po_detail_id',
        'produk_id',
        'nama_item',
        'deskripsi',
        'quantity',
        'satuan_id',
        'batch_number',
        'tanggal_expired',
        'keterangan'
    ];
    
    /**
     * Relasi ke Penerimaan Barang
     */
    public function penerimaan()
    {
        return $this->belongsTo(PenerimaanBarang::class, 'penerimaan_id');
    }
    
    /**
     * Relasi ke PO Detail
     */
    public function poDetail()
    {
        return $this->belongsTo(PurchaseOrderDetail::class, 'po_detail_id');
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