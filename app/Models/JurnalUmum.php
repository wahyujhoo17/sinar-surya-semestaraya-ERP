<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalUmum extends Model
{
    protected $table = 'jurnal_umum';

    protected $fillable = [
        'tanggal',
        'no_referensi',
        'akun_id',
        'debit',
        'kredit',
        'keterangan',
        'referensi_type',
        'referensi_id',
        'user_id'
    ];

    public function akun()
    {
        return $this->belongsTo(AkunAkuntansi::class, 'akun_id');
    }

    public function referensi()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
