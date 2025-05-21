<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer';

    protected $fillable = [
        'kode',
        'nama',
        'tipe',
        'jalan',
        'kota',
        'provinsi',
        'kode_pos',
        'negara',
        'company',
        'group',
        'industri',
        'sales_name',
        'alamat',
        'alamat_pengiriman',
        'telepon',
        'email',
        'npwp',
        'kontak_person',
        'no_hp_kontak',
        'catatan',
        'is_active',
    ];

    /**
     * Relasi ke Quotation
     */
    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'customer_id');
    }

    /**
     * Relasi ke Sales Order
     */
    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class, 'customer_id');
    }

    /**
     * Metode untuk menghasilkan alamat lengkap dari jalan, kota, provinsi, kode pos, dan negara
     * 
     * @return string
     */
    protected function generateFullAddress()
    {
        $parts = array_filter([
            $this->jalan,
            $this->kota,
            $this->provinsi,
            $this->kode_pos,
            $this->negara
        ]);

        return !empty($parts) ? implode(', ', $parts) : null;
    }

    /**
     * Mutator untuk menyimpan alamat lengkap secara otomatis dari komponen alamat
     * Dipanggil setiap kali model disimpan ke database
     */
    protected static function booted()
    {
        static::creating(function ($customer) {
            $customer->alamat = $customer->generateFullAddress();
        });

        static::updating(function ($customer) {
            // Cek jika komponen alamat berubah
            if (
                $customer->isDirty('jalan') || $customer->isDirty('kota') ||
                $customer->isDirty('provinsi') || $customer->isDirty('kode_pos') ||
                $customer->isDirty('negara')
            ) {
                $customer->alamat = $customer->generateFullAddress();
            }
        });
    }
}
