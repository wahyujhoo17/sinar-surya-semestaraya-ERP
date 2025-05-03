<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillOfMaterialDetail extends Model
{
    use HasFactory;
    
    protected $table = 'bill_of_material_details';
    
    protected $fillable = [
        'bom_id',
        'komponen_id',
        'quantity',
        'satuan_id',
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
     * Relasi ke Produk (Komponen)
     */
    public function komponen()
    {
        return $this->belongsTo(Produk::class, 'komponen_id');
    }
    
    /**
     * Relasi ke Satuan
     */
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }
}