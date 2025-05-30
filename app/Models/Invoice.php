<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoice';

    protected $fillable = [
        'nomor',
        'tanggal',
        'sales_order_id',
        'customer_id',
        'user_id',
        'subtotal',
        'diskon_persen',
        'diskon_nominal',
        'ppn',
        'ongkos_kirim',
        'total',
        'jatuh_tempo', // Renamed from tanggal_jatuh_tempo for consistency if it was different
        'status', // 'belum_bayar', 'sebagian', 'lunas'
        'catatan',
        'syarat_ketentuan',
        'kredit_terapkan' // New field to track credit notes applied
    ];

    protected $appends = ['sisa_piutang', 'status_display', 'status_pembayaran_class'];

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
     * Relasi ke Detail Invoice
     */
    public function details()
    {
        return $this->hasMany(InvoiceDetail::class, 'invoice_id');
    }

    /**
     * Relasi ke Pembayaran (menggunakan nama yang konsisten dengan controller)
     */
    public function pembayaranPiutang() // Changed from pembayaran() to pembayaranPiutang()
    {
        return $this->hasMany(PembayaranPiutang::class, 'invoice_id');
    }

    // Accessor for Sisa Piutang
    public function getSisaPiutangAttribute()
    {
        $totalPembayaran = $this->pembayaranPiutang()->sum('jumlah');
        $totalKredit = $this->kredit_terapkan ?? 0;
        return $this->total - $totalPembayaran - $totalKredit;
    }

    // Accessor for Status Display
    public function getStatusDisplayAttribute()
    {
        $sisaPiutang = $this->sisa_piutang; // Uses the above accessor
        $isOverdue = $this->jatuh_tempo && Carbon::parse($this->jatuh_tempo)->startOfDay()->lt(Carbon::today()->startOfDay());

        if (strtolower($this->status) === 'lunas' && $sisaPiutang <= 0) {
            return 'Lunas';
        }
        if ($isOverdue && strtolower($this->status) !== 'lunas') {
            return 'Jatuh Tempo';
        }
        if (strtolower($this->status) === 'sebagian' || strtolower($this->status) === 'lunas sebagian') {
            return 'Lunas Sebagian';
        }
        if (strtolower($this->status) === 'belum_bayar' || strtolower($this->status) === 'belum lunas') {
            // If status is 'belum_bayar' but sisa_piutang is 0 or less, it might be an edge case.
            // However, for display, if it's marked 'belum_bayar' and not overdue, show that.
            return 'Belum Lunas';
        }
        // Fallback for other cases or if status is not set but sisa_piutang indicates payment status
        if ($sisaPiutang <= 0) {
            return 'Lunas';
        }
        return ucfirst(str_replace('_', ' ', $this->status ?? 'Belum Bayar'));
    }

    // Accessor for Status CSS Class (Tailwind based)
    public function getStatusPembayaranClassAttribute()
    {
        $statusDisplay = $this->status_display; // Uses the above accessor

        switch ($statusDisplay) {
            case 'Lunas':
                return 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300';
            case 'Jatuh Tempo':
                return 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300';
            case 'Lunas Sebagian':
                return 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300';
            case 'Belum Lunas':
                return 'bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-300';
            default:
                return 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200';
        }
    }

    // Scope to get invoices that are not fully paid
    public function scopeBelumLunas($query)
    {
        return $query->where('status_pembayaran', '!=', 'Lunas');
    }

    // Scope to get invoices due in the next N days (e.g., for jatuh tempo minggu ini)
    public function scopeJatuhTempoDalamHari($query, $days)
    {
        return $query->where('status_pembayaran', '!=', 'Lunas')
            ->whereNotNull('jatuh_tempo')
            ->whereBetween('jatuh_tempo', [Carbon::today(), Carbon::today()->addDays($days)]);
    }

    /**
     * Apply a credit note amount to this invoice
     * 
     * @param float $amount The amount of credit to apply
     * @return void
     */
    public function applyCredit($amount)
    {
        // Update the credit amount applied to this invoice
        $this->kredit_terapkan = ($this->kredit_terapkan ?? 0) + $amount;

        // Update the status based on remaining balance
        $sisaPiutang = $this->getSisaPiutangAttribute();

        if ($sisaPiutang <= 0) {
            $this->status = 'lunas';
        } else {
            $this->status = 'sebagian';
        }

        $this->save();

        return $this;
    }
}
