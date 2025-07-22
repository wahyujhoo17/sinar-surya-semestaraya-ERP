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
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'budget' => 'decimal:2',
        'saldo' => 'decimal:2',
        'is_aktif' => 'boolean'
    ];

    /**
     * The attributes that should be cast to native types and formatted for JSON
     */
    protected $appends = [];

    /**
     * Get tanggal_mulai formatted for HTML input
     */
    public function getTanggalMulaiFormatAttribute()
    {
        return $this->tanggal_mulai ? $this->tanggal_mulai->format('Y-m-d') : null;
    }

    /**
     * Get tanggal_selesai formatted for HTML input
     */
    public function getTanggalSelesaiFormatAttribute()
    {
        return $this->tanggal_selesai ? $this->tanggal_selesai->format('Y-m-d') : null;
    }

    /**
     * Override toArray to ensure proper date formatting for JSON serialization
     */
    public function toArray()
    {
        $array = parent::toArray();

        // Format dates for HTML5 input compatibility
        if (isset($array['tanggal_mulai']) && $this->tanggal_mulai) {
            $array['tanggal_mulai'] = $this->tanggal_mulai->format('Y-m-d');
        }

        if (isset($array['tanggal_selesai']) && $this->tanggal_selesai) {
            $array['tanggal_selesai'] = $this->tanggal_selesai->format('Y-m-d');
        }

        return $array;
    }

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
            ->sum('nominal'); // Changed from 'jumlah' to 'nominal'
    }

    /**
     * Hitung total penggunaan dana project
     */
    public function getTotalPenggunaanAttribute()
    {
        return $this->transaksi()
            ->where('jenis', 'penggunaan')
            ->sum('nominal'); // Changed from 'jumlah' to 'nominal'
    }

    /**
     * Hitung total pengembalian dana dari project
     */
    public function getTotalPengembalianAttribute()
    {
        return $this->transaksi()
            ->where('jenis', 'pengembalian')
            ->sum('nominal'); // Changed from 'jumlah' to 'nominal'
    }

    /**
     * Hitung sisa budget
     */
    public function getSisaBudgetAttribute()
    {
        return $this->budget - $this->total_alokasi;
    }

    /**
     * Hitung saldo project (alokasi - penggunaan + pengembalian)
     */
    public function getSaldoAttribute()
    {
        return $this->total_alokasi - $this->total_penggunaan + $this->total_pengembalian;
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
     * Hitung persentase alokasi budget
     */
    public function getPersentaseAlokasiAttribute()
    {
        if ($this->budget == 0) return 0;
        return round(($this->total_alokasi / $this->budget) * 100, 2);
    }

    /**
     * Hitung persentase progress keseluruhan (alokasi + penggunaan)
     */
    public function getPersentaseProgressAttribute()
    {
        if ($this->budget == 0) return 0;
        $totalUsed = $this->total_alokasi + $this->total_penggunaan;
        return round(($totalUsed / $this->budget) * 100, 2);
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
