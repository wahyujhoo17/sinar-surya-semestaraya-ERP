<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningBank extends Model
{
    use HasFactory;
    
    protected $table = 'rekening_bank';
    
    protected $fillable = [
        'nama_bank',
        'nomor_rekening',
        'atas_nama',
        'cabang',
        'is_aktif',
        'saldo',
        'is_perusahaan' // true untuk rekening perusahaan, false untuk supplier/customer
    ];
    
    /**
     * Relasi ke transaksi bank
     */
    public function transaksi()
    {
        return $this->hasMany(TransaksiBank::class, 'rekening_id');
    }
}