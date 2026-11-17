<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory;

    protected $table = 'sales_order';

    protected $fillable = [
        'nomor',
        'nomor_po',
        'tanggal',
        'customer_id',
        'quotation_id',
        'user_id',
        'subtotal',
        'diskon_persen',
        'diskon_nominal',
        'diskon_tipe',
        'ppn',
        'total',
        'ongkos_kirim',
        'total_pembayaran',
        'kelebihan_bayar',
        'status_pembayaran',
        'status_pengiriman',
        'tanggal_kirim',
        'alamat_pengiriman',
        'catatan',
        'syarat_ketentuan',
        'terms_pembayaran',
        'terms_pembayaran_hari'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'tanggal' => 'datetime',
        'tanggal_kirim' => 'datetime',
    ];

    /**
     * Relasi ke Customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Relasi ke Quotation
     */
    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Detail Sales Order
     */
    public function details()
    {
        return $this->hasMany(SalesOrderDetail::class, 'sales_order_id');
    }

    /**
     * Relasi ke Work Order
     */
    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class, 'sales_order_id');
    }

    /**
     * Relasi ke Delivery Order
     */
    public function deliveryOrders()
    {
        return $this->hasMany(DeliveryOrder::class, 'sales_order_id');
    }

    /**
     * Relasi ke Invoice
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'sales_order_id');
    }

    /**
     * Persen diskon global untuk label dokumen (mis. "7,5%"), hanya jika persen itu
     * memang menghasilkan nominal diskonnya. Diskon yang diisi dalam Rupiah mengembalikan null
     * agar dokumen tidak menampilkan persen hasil pembulatan yang tidak cocok dengan nominal.
     */
    public function getDiskonPersenLabelAttribute(): ?string
    {
        $persen = (float) $this->diskon_persen;

        if ($persen <= 0 || (float) $this->diskon_nominal <= 0 || $this->diskon_tipe_efektif !== 'persen') {
            return null;
        }

        return rtrim(rtrim(number_format($persen, 2, ',', '.'), '0'), ',') . '%';
    }

    /**
     * Jenis diskon global: 'persen' atau 'nominal' (Rp).
     * Memakai kolom diskon_tipe; untuk SO lama (NULL / kolom belum ada) ditebak dari nilainya:
     * 'persen' bila nominal = subtotal × persen, selain itu 'nominal'.
     */
    public function getDiskonTipeEfektifAttribute(): string
    {
        $tipe = $this->attributes['diskon_tipe'] ?? null;
        if (in_array($tipe, ['persen', 'nominal'], true)) {
            return $tipe;
        }

        $persen = (float) $this->diskon_persen;
        $nominal = (float) $this->diskon_nominal;

        return ($persen > 0 && abs(round((float) $this->subtotal * $persen / 100) - $nominal) < 1)
            ? 'persen'
            : 'nominal';
    }

    /**
     * Apakah kolom diskon_tipe sudah ada (migration 2026_11_17_000000 sudah dijalankan).
     */
    public static function hasDiskonTipeColumn(): bool
    {
        static $hasColumn = null;

        return $hasColumn ??= \Illuminate\Support\Facades\Schema::hasColumn('sales_order', 'diskon_tipe');
    }

    /**
     * Relasi ke Retur Penjualan
     */
    public function returPenjualan()
    {
        return $this->hasMany(ReturPenjualan::class, 'sales_order_id');
    }

    /**
     * Get all payments across all invoices for this sales order
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getAllPayments()
    {
        $payments = collect();
        foreach ($this->invoices as $invoice) {
            if ($invoice->pembayaranPiutang) {
                $payments = $payments->merge($invoice->pembayaranPiutang);
            }
        }
        return $payments;
    }

    /**
     * Relasi ke Log Aktivitas
     */
    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'data_id')->where('modul', 'sales_order');
    }

    /**
     * Relasi ke UangMukaPenjualan
     */
    public function uangMukaPenjualan()
    {
        return $this->hasMany(UangMukaPenjualan::class, 'sales_order_id');
    }
}
