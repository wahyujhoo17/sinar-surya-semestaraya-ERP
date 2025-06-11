<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityControl extends Model
{
    use HasFactory;

    protected $table = 'quality_control';

    protected $fillable = [
        'nomor',
        'work_order_id',
        'tanggal',  // Changed from tanggal_inspeksi to tanggal
        'status',
        'jumlah_lolos',
        'jumlah_gagal',
        'catatan',
        'inspector_id',
    ];

    protected $casts = [
        'tanggal' => 'date',  // Changed from tanggal_inspeksi to tanggal
    ];

    /**
     * Relasi ke work order
     */
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id');
    }

    /**
     * Relasi ke user yang melakukan inspeksi
     */
    public function inspector()
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }

    /**
     * Relasi ke detail quality control
     */
    public function detail()
    {
        return $this->hasMany(QualityControlDetail::class, 'quality_control_id');
    }
}
