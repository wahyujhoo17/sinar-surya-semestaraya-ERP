<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UangMukaPenjualan extends Model
{
    use HasFactory;

    protected $table = 'uang_muka_penjualan';

    protected $fillable = [
        'nomor',
        'tanggal',
        'customer_id',
        'sales_order_id',
        'user_id',
        'jumlah',
        'jumlah_tersedia',
        'metode_pembayaran',
        'kas_id',
        'rekening_bank_id',
        'nomor_referensi',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'jumlah' => 'decimal:2',
        'jumlah_tersedia' => 'decimal:2',
    ];

    // Relasi ke Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi ke Sales Order (opsional)
    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke UangMukaAplikasi
    public function aplikasi()
    {
        return $this->hasMany(UangMukaAplikasi::class);
    }

    // Relasi ke Kas
    public function kas()
    {
        return $this->belongsTo(Kas::class);
    }

    // Relasi ke Rekening Bank
    public function rekeningBank()
    {
        return $this->belongsTo(RekeningBank::class);
    }

    // Method untuk mengecek apakah masih bisa diaplikasikan
    public function canBeApplied()
    {
        return $this->jumlah_tersedia > 0 && in_array($this->status, ['confirmed', 'partially_applied']);
    }

    // Method untuk menghitung total yang sudah diaplikasikan
    public function getTotalAplikasiAttribute()
    {
        return $this->aplikasi->sum('jumlah_aplikasi');
    }

    // Method untuk update status berdasarkan aplikasi
    public function updateStatus()
    {
        // Hitung total aplikasi langsung dari database untuk data yang paling update
        $totalAplikasi = (float) \App\Models\UangMukaAplikasi::where('uang_muka_penjualan_id', $this->id)
            ->sum('jumlah_aplikasi');

        $jumlah = (float) $this->jumlah;

        // Hitung jumlah tersedia
        $jumlahTersedia = 0;
        $status = 'confirmed';

        if ($totalAplikasi >= $jumlah) {
            $status = 'applied';
            $jumlahTersedia = 0;
        } elseif ($totalAplikasi > 0) {
            $status = 'partially_applied';
            $jumlahTersedia = $jumlah - $totalAplikasi;
        } else {
            $status = 'confirmed';
            $jumlahTersedia = $jumlah;
        }

        // Update menggunakan DB query untuk menghindari masalah casting
        DB::table('uang_muka_penjualan')
            ->where('id', $this->id)
            ->update([
                'status' => $status,
                'jumlah_tersedia' => $jumlahTersedia,
                'updated_at' => now()
            ]);

        // Refresh model
        $this->refresh();
    }

    // Scope untuk filter berdasarkan customer
    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    // Scope untuk yang masih bisa diaplikasikan
    public function scopeAvailable($query)
    {
        return $query->whereIn('status', ['confirmed', 'partially_applied'])
            ->where('jumlah_tersedia', '>', 0);
    }

    // Generate nomor otomatis
    public static function generateNomor()
    {
        $prefix = 'DP';
        $year = date('Y');
        $month = date('m');

        $lastNumber = self::where('nomor', 'like', $prefix . '/' . $year . $month . '%')
            ->orderBy('nomor', 'desc')
            ->first();

        if ($lastNumber) {
            $lastSequence = intval(substr($lastNumber->nomor, -4));
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }

        return $prefix . '/' . $year . $month . '/' . str_pad($newSequence, 4, '0', STR_PAD_LEFT);
    }
}
