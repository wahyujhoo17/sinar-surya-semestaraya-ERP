<?php
// filepath: app/Models/JenisProduk.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisProduk extends Model
{
    use HasFactory;

    protected $table = 'jenis_produk';

    protected $fillable = [
        'nama',
        'deskripsi',
        'is_active'
    ];

    /**
     * Relasi ke Produk
     */
    public function produks()
    {
        return $this->hasMany(Produk::class, 'jenis_id');
    }
}
