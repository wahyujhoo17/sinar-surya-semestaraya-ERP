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
        'telepon',
        'email',
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
}
