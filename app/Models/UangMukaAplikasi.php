<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UangMukaAplikasi extends Model
{
    use HasFactory;

    protected $table = 'uang_muka_aplikasi';

    protected $fillable = [
        'uang_muka_penjualan_id',
        'invoice_id',
        'jumlah_aplikasi',
        'tanggal_aplikasi',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_aplikasi' => 'datetime',
        'jumlah_aplikasi' => 'decimal:2',
    ];

    // Relasi ke UangMukaPenjualan
    public function uangMukaPenjualan()
    {
        return $this->belongsTo(UangMukaPenjualan::class);
    }

    // Relasi ke Invoice
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
