<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'purchase_order';

    protected $fillable = [
        'nomor',
        'tanggal',
        'supplier_id',
        'pr_id',
        'user_id',
        'subtotal',
        'diskon_persen',
        'diskon_nominal',
        'ppn',
        'ongkos_kirim',
        'total',
        'status',  // Added missing status field
        'status_pembayaran', // 'belum_bayar', 'sebagian', 'lunas', 'kelebihan_bayar'
        'kelebihan_bayar', // jumlah kelebihan bayar
        'status_penerimaan', // 'belum_diterima', 'sebagian', 'diterima'
        'tanggal_pengiriman',
        'alamat_pengiriman',
        'catatan',
        'syarat_ketentuan'
    ];


    /**
     * Relasi ke Supplier
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Purchase Request
     */
    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class, 'pr_id');
    }

    /**
     * Relasi ke Detail Purchase Order
     */
    public function details()
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'po_id');
    }

    /**
     * Relasi ke Penerimaan Barang
     */
    public function penerimaan()
    {
        return $this->hasMany(PenerimaanBarang::class, 'po_id');
    }

    /**
     * Relasi ke Pembayaran Hutang
     */
    public function pembayaran()
    {
        return $this->hasMany(PembayaranHutang::class, 'purchase_order_id');
    }

    /**
     * Relasi ke Retur Pembelian
     */
    public function retur()
    {
        return $this->hasMany(ReturPembelian::class, 'purchase_order_id');
    }

    /**
     * Relasi ke Pengembalian Dana
     */
    public function pengembalianDana()
    {
        return $this->hasMany(PengembalianDana::class, 'purchase_order_id');
    }
}
