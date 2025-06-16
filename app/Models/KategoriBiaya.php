<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBiaya extends Model
{
    use HasFactory;

    protected $table = 'kategori_biaya';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'akun_id'
    ];

    /**
     * Relasi ke Akun Akuntansi
     */
    public function akun()
    {
        return $this->belongsTo(AkunAkuntansi::class, 'akun_id');
    }

    /**
     * Relasi ke Biaya Operasional
     */
    public function biayaOperasional()
    {
        return $this->hasMany(BiayaOperasional::class, 'kategori_biaya_id');
    }
}
