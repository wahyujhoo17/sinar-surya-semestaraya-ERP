<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderMaterial extends Model
{
    use HasFactory;
    
    protected $table = 'work_order_materials';
    
    protected $fillable = [
        'work_order_id',
        'produk_id',
        'quantity',
        'quantity_terpakai',
        'satuan_id',
        'consumed'
    ];
    
    /**
     * Relasi ke Work Order
     */
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id');
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