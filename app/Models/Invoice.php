<?php

namespace App\Models;

use App\Traits\AutomaticJournalEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Invoice extends Model
{
    use HasFactory, AutomaticJournalEntry;

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

    /**
     * Relasi ke Nota Kredit (yang telah diterapkan ke invoice ini)
     */
    public function notaKredits()
    {
        return $this->belongsToMany(NotaKredit::class, 'nota_kredit_invoice')
            ->withPivot('applied_amount')
            ->withTimestamps();
    }

    /**
     * Check if this invoice has received credit from a specific nota kredit
     * 
     * @param int $notaKreditId The ID of the nota kredit to check
     * @return bool True if this invoice has received credit from the specified nota kredit
     */
    public function hasReceivedCreditFrom($notaKreditId)
    {
        return $this->notaKredits()->where('nota_kredit_id', $notaKreditId)->exists();
    }

    /**
     * Get the amount of credit applied from a specific nota kredit
     * 
     * @param int $notaKreditId The ID of the nota kredit
     * @return float The amount of credit applied from the specified nota kredit
     */
    public function getCreditAmountFrom($notaKreditId)
    {
        $notaKredit = $this->notaKredits()->where('nota_kredit_id', $notaKreditId)->first();
        return $notaKredit ? $notaKredit->pivot->applied_amount : 0;
    }

    /**
     * Membuat jurnal otomatis saat invoice dibuat
     */
    public function createAutomaticJournal()
    {
        try {
            // Mendapatkan ID akun dari konfigurasi
            $akunPiutangUsaha = config('accounting.penjualan.piutang_usaha');
            $akunPendapatanPenjualan = config('accounting.penjualan.pendapatan_penjualan');
            $akunPpnKeluaran = config('accounting.penjualan.ppn_keluaran');

            if (!$akunPiutangUsaha || !$akunPendapatanPenjualan) {
                Log::error("Akun untuk jurnal penjualan belum dikonfigurasi", [
                    'invoice_id' => $this->id,
                    'nomor' => $this->nomor
                ]);
                return false;
            }

            // Menyiapkan entri jurnal
            $entries = [];

            // Debit: Piutang Usaha
            $entries[] = [
                'akun_id' => $akunPiutangUsaha,
                'debit' => $this->total,
                'kredit' => 0
            ];

            // Hitung dasar pengenaan pajak (DPP) jika ada PPN
            $dpp = $this->subtotal - ($this->diskon_nominal ?? 0);
            $ppnAmount = $this->ppn ?? 0;

            // Kredit: Pendapatan Penjualan (DPP)
            $entries[] = [
                'akun_id' => $akunPendapatanPenjualan,
                'debit' => 0,
                'kredit' => $dpp
            ];

            // Jika ada PPN, tambahkan entri untuk PPN Keluaran
            if ($ppnAmount > 0 && $akunPpnKeluaran) {
                $entries[] = [
                    'akun_id' => $akunPpnKeluaran,
                    'debit' => 0,
                    'kredit' => $ppnAmount
                ];
            }

            // Jika ada ongkos kirim, tambahkan ke pendapatan (atau akun khusus jika ada)
            if (($this->ongkos_kirim ?? 0) > 0) {
                $entries[] = [
                    'akun_id' => $akunPendapatanPenjualan, // Bisa gunakan akun khusus untuk ongkos kirim jika ada
                    'debit' => 0,
                    'kredit' => $this->ongkos_kirim
                ];
            }

            // Buat jurnal otomatis dengan sinkronisasi saldo
            $this->createJournalEntries(
                $entries,
                $this->nomor,
                "Invoice penjualan: {$this->nomor}",
                $this->tanggal
            );

            return true;
        } catch (\Exception $e) {
            Log::error("Error saat membuat jurnal otomatis untuk invoice: " . $e->getMessage(), [
                'exception' => $e,
                'invoice_id' => $this->id,
                'nomor' => $this->nomor
            ]);
            return false;
        }
    }
}
