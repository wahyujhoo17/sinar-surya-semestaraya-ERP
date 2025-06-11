<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengambilanBahanBakuDetail extends Model
{
    use HasFactory;

    protected $table = 'pengambilan_bahan_baku_detail';

    protected $fillable = [
        'pengambilan_bahan_baku_id',
        'produk_id',
        'jumlah_diminta',
        'jumlah_diambil',
        'satuan_id',
        'batch_number',
        'lokasi_rak',
        'keterangan',
    ];

    /**
     * Relasi ke pengambilan bahan baku
     */
    public function pengambilanBahanBaku()
    {
        return $this->belongsTo(PengambilanBahanBaku::class, 'pengambilan_bahan_baku_id');
    }

    /**
     * Relasi ke produk
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    /**
     * Relasi ke satuan
     */
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }
}
