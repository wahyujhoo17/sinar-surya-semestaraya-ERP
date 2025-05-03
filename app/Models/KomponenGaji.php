<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomponenGaji extends Model
{
    use HasFactory;
    
    protected $table = 'komponen_gaji';
    
    protected $fillable = [
        'penggajian_id',
        'nama_komponen',
        'jenis',
        'nilai',
        'keterangan'
    ];
    
    /**
     * Relasi ke Penggajian
     */
    public function penggajian()
    {
        return $this->belongsTo(Penggajian::class, 'penggajian_id');
    }
}