<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier';

    protected $fillable = [
        'kode',
        'nama',
        'alamat',
        'telepon',
        'nama_kontak',
        'no_hp',
        'email',
        'type_produksi',
        'catatan',
        'is_active'
    ];

    /**
     * Relasi ke Purchase Order
     */
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'supplier_id');
    }

    /**
     * Relasi ke Penerimaan Barang
     */
    public function penerimaanBarang()
    {
        return $this->hasMany(PenerimaanBarang::class, 'supplier_id');
    }
}
