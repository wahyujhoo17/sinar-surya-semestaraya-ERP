<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengambilanBahanBaku extends Model
{
    use HasFactory;

    protected $table = 'pengambilan_bahan_baku';

    protected $fillable = [
        'nomor',
        'tanggal',
        'work_order_id',
        'gudang_id',
        'status',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Relasi ke work order
     */
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id');
    }

    /**
     * Relasi ke gudang
     */
    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }

    /**
     * Relasi ke detail pengambilan bahan baku
     */
    public function detail()
    {
        return $this->hasMany(PengambilanBahanBakuDetail::class, 'pengambilan_bahan_baku_id');
    }

    /**
     * Relasi ke user yang membuat
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
