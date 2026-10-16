<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillOfMaterial extends Model
{
    use HasFactory;

    protected $table = 'bill_of_materials';

    protected $fillable = [
        'produk_id',
        'nama',
        'kode',
        'deskripsi',
        'is_active',
        'versi',
        'overhead_cost'
    ];

    /**
     * Relasi ke Produk
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    /**
     * Relasi ke BOM Detail
     */
    public function details()
    {
        return $this->hasMany(BillOfMaterialDetail::class, 'bom_id');
    }

    /**
     * Relasi ke Work Order
     */
    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class, 'bom_id');
    }

    /**
     * Dapatkan nama pembuat BOM dari riwayat aktivitas log
     */
    public function getCreatorNameAttribute(): string
    {
        if (isset($this->attributes['creator_name']) && !empty($this->attributes['creator_name'])) {
            return $this->attributes['creator_name'];
        }

        // Cari di log_aktivitas berdasarkan bom_id atau kode BOM
        $log = \Illuminate\Support\Facades\DB::table('log_aktivitas')
            ->where(function ($q) {
                $q->where('detail', 'like', '%"bom_id":"' . $this->id . '"%')
                  ->orWhere('detail', 'like', '%"bom_id":' . $this->id . '%')
                  ->orWhere('detail', 'like', '%' . $this->kode . '%');
            })
            ->join('users', 'log_aktivitas.user_id', '=', 'users.id')
            ->orderBy('log_aktivitas.created_at', 'asc')
            ->select('users.name')
            ->first();

        if ($log && !empty($log->name)) {
            return $log->name;
        }

        return 'Staff Produksi';
    }
}
