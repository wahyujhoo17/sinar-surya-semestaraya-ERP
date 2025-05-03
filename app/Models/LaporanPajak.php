<?php

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPajak extends Model
{
    protected $table = 'laporan_pajak';

    protected $fillable = [
        'jenis',
        'nomor',
        'tanggal',
        'periode_awal',
        'periode_akhir',
        'nilai',
        'status',
        'keterangan',
        'file_path',
        'user_id'
    ];
}
