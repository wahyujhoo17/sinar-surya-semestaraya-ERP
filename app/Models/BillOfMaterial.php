<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillOfMaterial extends Model
{
    use HasFactory;
    
    protected $table = 'bill_of_materials';
    
    protected $fillable = [
        'produk_id',
        'nama',
        'kode',
        'deskripsi',
        'is_active',
        'versi'
    ];
    
    /**
     * Relasi ke Produk
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
    
    /**
     * Relasi ke BOM Detail
     */
    public function details()
    {
        return $this->hasMany(BillOfMaterialDetail::class, 'bom_id');
    }
    
    /**
     * Relasi ke Work Order
     */
    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class, 'bom_id');
    }
}