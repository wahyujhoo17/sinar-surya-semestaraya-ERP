<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    use HasFactory;
    
    protected $table = 'satuan';
    
    protected $fillable = [
        'nama',
        'kode',
        'deskripsi'
    ];
    
    /**
     * Relasi ke Produk
     */
    public function produk()
    {
        return $this->hasMany(Produk::class, 'satuan_id');
    }
}