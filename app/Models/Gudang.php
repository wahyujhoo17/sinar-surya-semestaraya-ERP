<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gudang extends Model
{
    use HasFactory;
    
    protected $table = 'gudang';
    
    protected $fillable = [
        'nama',
        'kode',
        'alamat',
        'telepon',
        'penanggung_jawab',
        'jenis', // 'utama', 'cabang', 'produksi'
        'is_active'
    ];
    
    /**
     * Relasi ke StokProduk
     */
    public function stok()
    {
        return $this->hasMany(StokProduk::class, 'gudang_id');
    }
    
    /**
     * Relasi ke User penanggung jawab
     */
    public function penanggungjawab()
    {
        return $this->belongsTo(User::class, 'penanggung_jawab');
    }
}