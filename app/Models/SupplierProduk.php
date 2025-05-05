<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierProduk extends Model
{
    protected $table = 'supplier_produks';
    protected $fillable = ['supplier_id', 'produk_id'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}