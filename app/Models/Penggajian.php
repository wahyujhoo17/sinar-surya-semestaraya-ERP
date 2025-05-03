<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penggajian extends Model
{
    use HasFactory;
    
    protected $table = 'penggajian';
    
    protected $fillable = [
        'karyawan_id',
        'bulan',
        'tahun',
        'gaji_pokok',
        'tunjangan',
        'bonus',
        'lembur',
        'potongan',
        'total_gaji',
        'tanggal_bayar',
        'status', // 'draft', 'disetujui', 'dibayar'
        'catatan',
        'disetujui_oleh'
    ];
    
    /**
     * Relasi ke Karyawan
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }
    
    /**
     * Relasi ke User yang menyetujui
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
    
    /**
     * Relasi ke Detail Komponen Gaji
     */
    public function komponenGaji()
    {
        return $this->hasMany(KomponenGaji::class, 'penggajian_id');
    }
}