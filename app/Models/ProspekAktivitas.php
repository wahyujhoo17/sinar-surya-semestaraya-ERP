<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProspekAktivitas extends Model
{
    use HasFactory;

    protected $table = 'prospek_aktivitas';
    protected $guarded = [];

    protected $casts = [
        'tanggal' => 'datetime',
        'tanggal_followup' => 'datetime',
        'attachments' => 'array',
    ];

    // Relasi dengan prospek
    public function prospek()
    {
        return $this->belongsTo(Prospek::class, 'prospek_id');
    }

    // Relasi dengan customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    // Relasi dengan user (yang menambahkan aktivitas)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Mendapatkan nama entitas (prospek atau customer)
    public function getEntityNameAttribute()
    {
        if ($this->customer_id) {
            return $this->customer ? $this->customer->nama : 'Customer Terhapus';
        } elseif ($this->prospek_id) {
            return $this->prospek ? $this->prospek->nama_prospek : 'Prospek Terhapus';
        }
        return 'N/A';
    }

    // Mendapatkan tipe entitas
    public function getEntityTypeAttribute()
    {
        if ($this->customer_id) {
            return 'customer';
        } elseif ($this->prospek_id) {
            return 'prospek';
        }
        return null;
    }

    // Tipe aktivitas
    const TIPE_TELEPON = 'telepon';
    const TIPE_EMAIL = 'email';
    const TIPE_PERTEMUAN = 'pertemuan';
    const TIPE_PRESENTASI = 'presentasi';
    const TIPE_PENAWARAN = 'penawaran';
    const TIPE_LAINNYA = 'lainnya';

    // Dapatkan list tipe aktivitas untuk dropdown
    public static function getTipeList()
    {
        return [
            self::TIPE_TELEPON => 'Telepon',
            self::TIPE_EMAIL => 'Email',
            self::TIPE_PERTEMUAN => 'Pertemuan',
            self::TIPE_PRESENTASI => 'Presentasi',
            self::TIPE_PENAWARAN => 'Penawaran',
            self::TIPE_LAINNYA => 'Lainnya',
        ];
    }

    // Status followup
    const STATUS_MENUNGGU = 'menunggu';
    const STATUS_SELESAI = 'selesai';
    const STATUS_DIBATALKAN = 'dibatalkan';

    // Dapatkan list status followup untuk dropdown
    public static function getStatusList()
    {
        return [
            self::STATUS_MENUNGGU => 'Menunggu',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_DIBATALKAN => 'Dibatalkan',
        ];
    }
}
