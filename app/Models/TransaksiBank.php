<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiBank extends Model
{
    use HasFactory;
    
    protected $table = 'transaksi_bank';
    
    protected $fillable = [
        'tanggal',
        'rekening_id',
        'jenis', // 'masuk', 'keluar'
        'jumlah',
        'keterangan',
        'no_referensi',
        'related_id', // ID dari dokumen terkait (Invoice, PO)
        'related_type', // nama model dari dokumen terkait
        'user_id'
    ];
    
    /**
     * Relasi ke RekeningBank
     */
    public function rekening()
    {
        return $this->belongsTo(RekeningBank::class, 'rekening_id');
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