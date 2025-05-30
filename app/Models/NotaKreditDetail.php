<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaKreditDetail extends Model
{
    use HasFactory;

    protected $table = 'nota_kredit_detail';

    protected $fillable = [
        'nota_kredit_id',
        'produk_id',
        'quantity',
        'satuan_id',
        'harga',
        'subtotal'
    ];

    /**
     * Relasi ke Nota Kredit
     */
    public function notaKredit()
    {
        return $this->belongsTo(NotaKredit::class, 'nota_kredit_id');
    }

    /**
     * Relasi ke Produk
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    /**
     * Relasi ke Satuan
     */
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }
}
