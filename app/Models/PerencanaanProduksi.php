<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerencanaanProduksi extends Model
{
    use HasFactory;

    protected $table = 'perencanaan_produksi';

    protected $fillable = [
        'nomor',
        'tanggal',
        'sales_order_id',
        'catatan',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'approved_at' => 'datetime',
    ];

    /**
     * Relasi ke Sales Order
     */
    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }

    /**
     * Relasi ke detail perencanaan produksi
     */
    public function detailPerencanaan()
    {
        return $this->hasMany(PerencanaanProduksiDetail::class, 'perencanaan_produksi_id');
    }

    /**
     * Relasi ke work orders
     */
    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class, 'perencanaan_produksi_id');
    }

    /**
     * Relasi ke user yang membuat
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke user yang menyetujui
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
