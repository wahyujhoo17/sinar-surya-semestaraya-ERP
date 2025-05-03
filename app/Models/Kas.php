<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    use HasFactory;
    
    protected $table = 'kas';
    
    protected $fillable = [
        'nama',
        'deskripsi',
        'saldo',
        'is_aktif'
    ];
    
    /**
     * Relasi ke transaksi kas
     */
    public function transaksi()
    {
        return $this->hasMany(TransaksiKas::class, 'kas_id');
    }
}