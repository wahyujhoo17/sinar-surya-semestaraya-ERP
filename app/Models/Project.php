<?php

namespace App\Models;

use App\Traits\AutomaticJournalEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory, AutomaticJournalEntry;

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'budget',
        'saldo',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'customer_id',
        'sales_order_id',
        'pic_internal',
        'pic_customer',
        'metadata',
        'is_aktif'
    ];

    protected $casts = [
        'metadata' => 'array',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'budget' => 'decimal:2',
        'saldo' => 'decimal:2',
        'is_aktif' => 'boolean'
    ];

    /**
     * Relasi ke Customer
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relasi ke Sales Order
     */
    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class);
    }

    /**
     * Relasi ke transaksi project
     */
    public function transaksi(): HasMany
    {
        return $this->hasMany(TransaksiProject::class);
    }

    /**
     * Scope untuk project aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }

    /**
     * Scope berdasarkan status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Hitung total alokasi dana ke project
     */
    public function getTotalAlokasiAttribute()
    {
        return $this->transaksi()
            ->where('jenis', 'alokasi')
            ->sum('nominal');
    }

    /**
     * Hitung total penggunaan dana project
     */
    public function getTotalPenggunaanAttribute()
    {
        return $this->transaksi()
            ->where('jenis', 'penggunaan')
            ->sum('nominal');
    }

    /**
     * Hitung total pengembalian dana dari project
     */
    public function getTotalPengembalianAttribute()
    {
        return $this->transaksi()
            ->where('jenis', 'pengembalian')
            ->sum('nominal');
    }

    /**
     * Hitung sisa budget
     */
    public function getSisaBudgetAttribute()
    {
        return $this->budget - $this->total_alokasi;
    }

    /**
     * Hitung persentase penggunaan budget
     */
    public function getPersentasePenggunaanAttribute()
    {
        if ($this->budget == 0) return 0;
        return round(($this->total_penggunaan / $this->budget) * 100, 2);
    }

    /**
     * Generate kode project otomatis
     */
    public static function generateKode()
    {
        $prefix = 'PROJ';
        $year = date('Y');
        $month = date('m');

        $lastProject = self::where('kode', 'like', "{$prefix}-{$year}-{$month}-%")
            ->orderBy('kode', 'desc')
            ->first();

        if ($lastProject) {
            $lastNumber = intval(substr($lastProject->kode, -3));
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return "{$prefix}-{$year}-{$month}-{$newNumber}";
    }

    /**
     * Boot method untuk auto generate kode
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->kode)) {
                $project->kode = self::generateKode();
            }
        });
    }
}
