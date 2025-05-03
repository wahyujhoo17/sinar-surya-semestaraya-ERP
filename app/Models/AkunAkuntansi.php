<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkunAkuntansi extends Model
{
    protected $table = 'akun_akuntansi';

    protected $fillable = [
        'kode',
        'nama',
        'kategori',
        'tipe',
        'parent_id',
        'is_active'
    ];

    public function parent()
    {
        return $this->belongsTo(AkunAkuntansi::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(AkunAkuntansi::class, 'parent_id');
    }

    public function jurnalEntries()
    {
        return $this->hasMany(JurnalUmum::class, 'akun_id');
    }
}
