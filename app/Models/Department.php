<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    
    protected $table = 'department';
    
    protected $fillable = [
        'nama',
        'kode',
        'deskripsi',
        'parent_id',
        'is_active'
    ];
    
    /**
     * Relasi ke Department Parent
     */
    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }
    
    /**
     * Relasi ke Department Child
     */
    public function children()
    {
        return $this->hasMany(Department::class, 'parent_id');
    }
    
    /**
     * Relasi ke Karyawan
     */
    public function karyawan()
    {
        return $this->hasMany(Karyawan::class, 'department_id');
    }
}