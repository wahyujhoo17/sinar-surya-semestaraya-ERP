<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    use HasFactory;
    
    protected $table = 'log_aktivitas';
    
    protected $fillable = [
        'user_id',
        'aktivitas',
        'modul',
        'data_id',
        'ip_address',
        'detail'
    ];
    
    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}