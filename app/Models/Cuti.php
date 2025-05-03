<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    use HasFactory;
    
    protected $table = 'cuti';
    
    protected $fillable = [
        'karyawan_id',
        'jenis_cuti',
        'tanggal_mulai',
        'tanggal_selesai',
        'jumlah_hari',
        'keterangan',
        'status', // 'diajukan', 'disetujui', 'ditolak'
        'disetujui_oleh',
        'catatan_persetujuan'
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
}