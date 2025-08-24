<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBundle extends Model
{
    use HasFactory;

    protected $table = 'product_bundles';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'harga_bundle',
        'harga_normal',
        'diskon_persen',
        'is_active',
        'gambar',
        'kategori_id'
    ];

    protected $casts = [
        'harga_bundle' => 'decimal:2',
        'harga_normal' => 'decimal:2',
        'diskon_persen' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    /**
     * Relasi ke kategori produk
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class, 'kategori_id');
    }

    /**
     * Relasi ke bundle items
     */
    public function items()
    {
        return $this->hasMany(ProductBundleItem::class, 'bundle_id');
    }

    /**
     * Relasi ke produk melalui bundle items
     */
    public function produk()
    {
        return $this->belongsToMany(Produk::class, 'product_bundle_items', 'bundle_id', 'produk_id')
            ->withPivot('quantity', 'harga_satuan')
            ->withTimestamps();
    }

    /**
     * Hitung total harga normal (jika beli terpisah)
     */
    public function getTotalHargaNormalAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->produk->harga_jual * $item->quantity;
        });
    }

    /**
     * Hitung persentase hemat
     */
    public function getPersentaseHematAttribute()
    {
        $totalNormal = $this->total_harga_normal;
        if ($totalNormal > 0) {
            return (($totalNormal - $this->harga_bundle) / $totalNormal) * 100;
        }
        return 0;
    }

    /**
     * Check apakah bundle bisa dijual (stok mencukupi)
     */
    public function isAvailable($quantity = 1, $gudangId = null)
    {
        foreach ($this->items as $item) {
            $requiredQty = $item->quantity * $quantity;

            if ($gudangId) {
                $availableStock = $item->produk->stok()
                    ->where('gudang_id', $gudangId)
                    ->sum('jumlah');
            } else {
                $availableStock = $item->produk->total_stok;
            }

            if ($availableStock < $requiredQty) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get minimum quantity yang bisa dijual berdasarkan stok
     */
    public function getMaxAvailableQuantity($gudangId = null)
    {
        $maxQty = PHP_INT_MAX;

        foreach ($this->items as $item) {
            if ($gudangId) {
                $availableStock = $item->produk->stok()
                    ->where('gudang_id', $gudangId)
                    ->sum('jumlah');
            } else {
                $availableStock = $item->produk->total_stok;
            }

            $possibleQty = floor($availableStock / $item->quantity);
            $maxQty = min($maxQty, $possibleQty);
        }

        return $maxQty == PHP_INT_MAX ? 0 : $maxQty;
    }

    /**
     * Get detail stok untuk setiap produk dalam bundle
     */
    public function getStokDetail($gudangId = null)
    {
        $details = [];

        foreach ($this->items as $item) {
            $stokTersedia = $item->produk->stokTersedia($gudangId);
            $details[] = [
                'produk_id' => $item->produk_id,
                'produk_nama' => $item->produk->nama,
                'quantity_required' => $item->quantity,
                'stok_tersedia' => $stokTersedia,
                'is_sufficient' => $stokTersedia >= $item->quantity,
                'max_bundle_qty' => $item->quantity > 0 ? floor($stokTersedia / $item->quantity) : 0
            ];
        }

        return $details;
    }

    /**
     * Scope untuk bundle aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
