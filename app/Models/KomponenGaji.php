<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomponenGaji extends Model
{
    use HasFactory;

    protected $table = 'komponen_gaji';

    protected $fillable = [
        'penggajian_id',
        'nama_komponen',
        'jenis',
        'nilai',
        'keterangan',
        'sales_order_id',
        'cashback_nominal',
        'overhead_persen',
        'netto_penjualan_original',
        'netto_beli_original',
        'netto_penjualan_adjusted',
        'netto_beli_adjusted',
        'margin_persen',
        'komisi_rate',
        'product_details',
        'sales_ppn',
        'has_sales_ppn'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'nilai' => 'decimal:2',
        'cashback_nominal' => 'decimal:2',
        'overhead_persen' => 'decimal:2',
        'netto_penjualan_original' => 'decimal:2',
        'netto_beli_original' => 'decimal:2',
        'netto_penjualan_adjusted' => 'decimal:2',
        'netto_beli_adjusted' => 'decimal:2',
        'margin_persen' => 'decimal:4',
        'komisi_rate' => 'decimal:2',
        'product_details' => 'array',
        'sales_ppn' => 'decimal:2',
        'has_sales_ppn' => 'boolean'
    ];

    /**
     * Relasi ke Penggajian
     */
    public function penggajian()
    {
        return $this->belongsTo(Penggajian::class, 'penggajian_id');
    }

    /**
     * Relasi ke Sales Order
     */
    public function salesOrder()
    {
        return $this->belongsTo(\App\Models\SalesOrder::class, 'sales_order_id');
    }
}
