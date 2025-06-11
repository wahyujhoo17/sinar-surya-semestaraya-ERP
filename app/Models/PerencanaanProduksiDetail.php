<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerencanaanProduksiDetail extends Model
{
    use HasFactory;

    protected $table = 'perencanaan_produksi_detail';

    protected $fillable = [
        'perencanaan_produksi_id',
        'produk_id',
        'jumlah',
        'satuan_id',
        'stok_tersedia',
        'jumlah_produksi',
        'keterangan',
    ];

    /**
     * Relasi ke perencanaan produksi
     */
    public function perencanaanProduksi()
    {
        return $this->belongsTo(PerencanaanProduksi::class, 'perencanaan_produksi_id');
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
