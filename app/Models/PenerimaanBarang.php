<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanBarang extends Model
{
    use HasFactory;
    
    protected $table = 'penerimaan_barang';
    
    protected $fillable = [
        'nomor',
        'tanggal',
        'po_id',
        'supplier_id',
        'user_id',
        'gudang_id',
        'nomor_surat_jalan',
        'tanggal_surat_jalan',
        'catatan',
        'status' // 'draft', 'selesai'
    ];
    
    /**
     * Relasi ke Purchase Order
     */
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }
    
    /**
     * Relasi ke Supplier
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    
    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Relasi ke Gudang
     */
    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }
    
    /**
     * Relasi ke Detail Penerimaan Barang
     */
    public function details()
    {
        return $this->hasMany(PenerimaanBarangDetail::class, 'penerimaan_id');
    }
}