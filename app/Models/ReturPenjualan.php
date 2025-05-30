<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturPenjualan extends Model
{
    use HasFactory;

    protected $table = 'retur_penjualan';

    protected $fillable = [
        'nomor',
        'tanggal',
        'sales_order_id',
        'customer_id',
        'user_id',
        'catatan',
        'total',
        'status', // 'draft', 'menunggu_persetujuan', 'disetujui', 'ditolak', 'diproses', 'menunggu_pengiriman', 'menunggu_barang_pengganti', 'selesai'
        'tipe_retur', // 'pengembalian_dana', 'tukar_barang'
        'requires_qc',
        'qc_passed',
        'qc_notes',
        'qc_by_user_id',
        'qc_at'
    ];

    /**
     * Relasi ke Sales Order
     */
    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }

    /**
     * Relasi ke Customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Detail Retur Penjualan
     */
    public function details()
    {
        return $this->hasMany(ReturPenjualanDetail::class, 'retur_id');
    }

    /**
     * Relasi ke User yang melakukan QC
     */
    public function qcByUser()
    {
        return $this->belongsTo(User::class, 'qc_by_user_id');
    }

    /**
     * Relasi ke Nota Kredit
     */
    public function notaKredit()
    {
        return $this->hasOne(NotaKredit::class, 'retur_penjualan_id');
    }

    /**
     * Relasi ke Item Pengganti
     */
    public function barangPengganti()
    {
        return $this->hasMany(ReturPenjualanPengganti::class, 'retur_id');
    }
}
