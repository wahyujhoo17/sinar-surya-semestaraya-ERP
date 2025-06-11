<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityControlDetail extends Model
{
    use HasFactory;

    protected $table = 'quality_control_detail';

    protected $fillable = [
        'quality_control_id',
        'parameter',
        'standar',
        'hasil',
        'status',
        'keterangan',
    ];

    /**
     * Relasi ke quality control
     */
    public function qualityControl()
    {
        return $this->belongsTo(QualityControl::class, 'quality_control_id');
    }
}
