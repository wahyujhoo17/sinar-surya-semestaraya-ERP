<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationLog extends Model
{
    use HasFactory;

    protected $table = 'quotation_logs';

    protected $fillable = [
        'quotation_id',
        'user_id',
        'tipe',
        'deskripsi',
        'detail',
    ];

    // Relasi dengan quotation
    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    // Relasi dengan user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
