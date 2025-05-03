<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferBarang extends Model
{
    use HasFactory;
    
    protected $table = 'transfer_barang';
    
    protected $fillable = [
        'nomor',
        'tanggal',
        'gudang_asal_id',
        'gudang_tujuan_id',
        'user_id',
        'status', // 'draft', 'diproses', 'selesai'
        'catatan'
    ];
    
    /**
     * Relasi ke Gudang Asal
     */
    public function gudangAsal()
    {
        return $this->belongsTo(Gudang::class, 'gudang_asal_id');
    }
    
    /**
     * Relasi ke Gudang Tujuan
     */
    public function gudangTujuan()
    {
        return $this->belongsTo(Gudang::class, 'gudang_tujuan_id');
    }
    
    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Relasi ke Detail Transfer Barang
     */
    public function details()
    {
        return $this->hasMany(TransferBarangDetail::class, 'transfer_id');
    }
}