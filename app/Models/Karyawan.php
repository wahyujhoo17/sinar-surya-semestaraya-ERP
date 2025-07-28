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
        'gaji_pokok',
        'tunjangan_btn',
        'tunjangan_keluarga',
        'tunjangan_jabatan',
        'tunjangan_transport',
        'tunjangan_makan',
        'tunjangan_pulsa',
        'default_tunjangan',
        'default_bonus',
        'default_lembur_rate',
        'bpjs',
        'default_potongan',
        'tanggal_masuk',
        'tanggal_keluar',
        'status', // 'aktif', 'nonaktif', 'cuti', 'keluar'
        'foto',
        'user_id'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
        'tanggal_keluar' => 'date',
        'gaji_pokok' => 'decimal:0',
        'tunjangan_btn' => 'decimal:0',
        'tunjangan_keluarga' => 'decimal:0',
        'tunjangan_jabatan' => 'decimal:0',
        'tunjangan_transport' => 'decimal:0',
        'tunjangan_makan' => 'decimal:0',
        'tunjangan_pulsa' => 'decimal:0',
        'default_tunjangan' => 'decimal:0',
        'default_bonus' => 'decimal:0',
        'default_lembur_rate' => 'decimal:0',
        'bpjs' => 'decimal:0',
        'default_potongan' => 'decimal:0',
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
        return $this->belongsTo(Jabatan::class, 'jabatan_id', 'id');
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