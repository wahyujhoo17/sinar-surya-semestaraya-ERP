<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;
    
    protected $table = 'jabatan';
    
    protected $fillable = [
        'nama',
        'kode',
        'deskripsi',
        'is_active'
    ];
    
    /**
     * Relasi ke Karyawan
     */
    public function karyawan()
    {
        return $this->hasMany(Karyawan::class, 'jabatan_id');
    }
}