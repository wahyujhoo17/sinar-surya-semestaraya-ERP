<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;
    
    protected $table = 'karyawan';
    
    protected $fillable = [
        'nip',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'telepon',
        'email',
        'department_id',
        'jabatan_id',
        'tanggal_masuk',
        'tanggal_keluar',
        'status', // 'aktif', 'nonaktif', 'cuti', 'keluar'
        'foto',
        'user_id'
    ];
    
    /**
     * Relasi ke Department
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    
    /**
     * Relasi ke Jabatan
     */
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }
    
    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Relasi ke Absensi
     */
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'karyawan_id');
    }
    
    /**
     * Relasi ke Cuti
     */
    public function cuti()
    {
        return $this->hasMany(Cuti::class, 'karyawan_id');
    }
}