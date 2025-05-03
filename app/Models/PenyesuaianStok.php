<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenyesuaianStok extends Model
{
    use HasFactory;
    
    protected $table = 'penyesuaian_stok';
    
    protected $fillable = [
        'nomor',
        'tanggal',
        'gudang_id',
        'user_id',
        'catatan',
        'status' // 'draft', 'disetujui', 'selesai'
    ];
    
    /**
     * Relasi ke Gudang
     */
    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }
    
    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Relasi ke Detail Penyesuaian Stok
     */
    public function details()
    {
        return $this->hasMany(PenyesuaianStokDetail::class, 'penyesuaian_id');
    }
}