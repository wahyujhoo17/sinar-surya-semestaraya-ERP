<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiKas extends Model
{
    use HasFactory;
    
    protected $table = 'transaksi_kas';
    
    protected $fillable = [
        'tanggal',
        'kas_id',
        'jenis', // 'masuk', 'keluar'
        'jumlah',
        'keterangan',
        'no_bukti',
        'related_id', // ID dari dokumen terkait
        'related_type', // nama model dari dokumen terkait
        'user_id'
    ];
    
    /**
     * Relasi ke Kas
     */
    public function kas()
    {
        return $this->belongsTo(Kas::class, 'kas_id');
    }
    
    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Relasi polimorfik ke dokumen terkait
     */
    public function related()
    {
        return $this->morphTo();
    }
}