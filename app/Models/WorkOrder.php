<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;
    
    protected $table = 'work_order';
    
    protected $fillable = [
        'nomor',
        'tanggal',
        'bom_id',
        'sales_order_id',
        'produk_id',
        'gudang_produksi_id',
        'gudang_hasil_id',
        'user_id',
        'quantity',
        'satuan_id',
        'tanggal_mulai',
        'deadline',
        'tanggal_selesai',
        'status',
        'catatan'
    ];
    
    /**
     * Relasi ke Bill of Material
     */
    public function bom()
    {
        return $this->belongsTo(BillOfMaterial::class, 'bom_id');
    }
    
    /**
     * Relasi ke Sales Order
     */
    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }
    
    /**
     * Relasi ke Produk
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
    
    /**
     * Relasi ke Gudang Produksi
     */
    public function gudangProduksi()
    {
        return $this->belongsTo(Gudang::class, 'gudang_produksi_id');
    }
    
    /**
     * Relasi ke Gudang Hasil
     */
    public function gudangHasil()
    {
        return $this->belongsTo(Gudang::class, 'gudang_hasil_id');
    }
    
    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Relasi ke Satuan
     */
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }
    
    /**
     * Relasi ke Material yang Digunakan
     */
    public function materials()
    {
        return $this->hasMany(WorkOrderMaterial::class, 'work_order_id');
    }
}