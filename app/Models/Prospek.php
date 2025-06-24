<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prospek extends Model
{
    use HasFactory;

    protected $table = 'prospek';
    protected $guarded = [];

    protected $casts = [
        'tanggal_kontak' => 'datetime',
        'tanggal_followup' => 'datetime',
    ];

    // Relasi dengan customer jika ada
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    // Relasi dengan user (sales penanggung jawab)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Status prospek
    const STATUS_BARU = 'baru';
    const STATUS_TERTARIK = 'tertarik';
    const STATUS_NEGOSIASI = 'negosiasi';
    const STATUS_MENOLAK = 'menolak';
    const STATUS_MENJADI_CUSTOMER = 'menjadi_customer';

    // Sumber prospek
    const SUMBER_WEBSITE = 'website';
    const SUMBER_REFERRAL = 'referral';
    const SUMBER_PAMERAN = 'pameran';
    const SUMBER_MEDIA_SOSIAL = 'media_sosial';
    const SUMBER_COLD_CALL = 'cold_call';
    const SUMBER_LAINNYA = 'lainnya';

    // Dapatkan list status untuk dropdown
    public static function getStatusList()
    {
        return [
            self::STATUS_BARU => 'Baru',
            self::STATUS_TERTARIK => 'Tertarik',
            self::STATUS_NEGOSIASI => 'Negosiasi',
            self::STATUS_MENOLAK => 'Menolak',
            self::STATUS_MENJADI_CUSTOMER => 'Menjadi Customer',
        ];
    }

    // Dapatkan list sumber untuk dropdown
    public static function getSumberList()
    {
        return [
            self::SUMBER_WEBSITE => 'Website',
            self::SUMBER_REFERRAL => 'Referral',
            self::SUMBER_PAMERAN => 'Pameran',
            self::SUMBER_MEDIA_SOSIAL => 'Media Sosial',
            self::SUMBER_COLD_CALL => 'Cold Call',
            self::SUMBER_LAINNYA => 'Lainnya',
        ];
    }

    // Relasi dengan aktivitas
    public function aktivitas()
    {
        return $this->hasMany(ProspekAktivitas::class, 'prospek_id')->orderBy('tanggal', 'desc');
    }
}
