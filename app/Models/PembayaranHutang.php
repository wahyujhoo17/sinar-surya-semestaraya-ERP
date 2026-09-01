<?php

namespace App\Models;

use App\Traits\AutomaticJournalEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class PembayaranHutang extends Model
{
    use HasFactory, AutomaticJournalEntry;

    protected $table = 'pembayaran_hutang';

    protected $fillable = [
        'nomor',
        'tanggal',
        'purchase_order_id',
        'supplier_id',
        'jumlah',
        'metode_pembayaran',
        'no_referensi',
        'catatan',
        'user_id',
        'kas_id',
        'rekening_id'
    ];

    /**
     * Relasi ke Detail Pembayaran Hutang
     */
    public function details()
    {
        return $this->hasMany(PembayaranHutangDetail::class, 'pembayaran_hutang_id');
    }

    /**
     * Relasi many-to-many ke Purchase Orders via Detail
     */
    public function purchaseOrders()
    {
        return $this->belongsToMany(PurchaseOrder::class, 'pembayaran_hutang_detail', 'pembayaran_hutang_id', 'purchase_order_id')
            ->withPivot('jumlah', 'catatan')
            ->withTimestamps();
    }

    /**
     * Relasi ke Purchase Order (Legacy / Single PO Fallback)
     */
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

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
     * Relasi ke Kas
     */
    public function kas()
    {
        return $this->belongsTo(Kas::class, 'kas_id');
    }

    /**
     * Relasi ke Rekening Bank
     */
    public function rekeningBank()
    {
        return $this->belongsTo(RekeningBank::class, 'rekening_id');
    }

    /**
     * Get the cash transaction associated with this payment
     */
    public function transaksiKas()
    {
        return $this->morphOne(TransaksiKas::class, 'related');
    }

    /**
     * Get the bank transaction associated with this payment
     */
    public function transaksiBank()
    {
        return $this->morphOne(TransaksiBank::class, 'related');
    }

    /**
     * Membuat jurnal otomatis saat pembayaran hutang dibuat
     */
    public function createAutomaticJournal()
    {
        try {
            // Mendapatkan ID akun dari konfigurasi database (fallback ke config file)
            $akunHutangUsaha = \App\Models\AccountingConfiguration::get('pembayaran_hutang.hutang_usaha')
                ?? config('accounting.pembayaran_hutang.hutang_usaha');
            $akunKasDefault = \App\Models\AccountingConfiguration::get('pembayaran_hutang.kas')
                ?? config('accounting.pembayaran_hutang.kas');
            $akunBankDefault = \App\Models\AccountingConfiguration::get('pembayaran_hutang.bank')
                ?? config('accounting.pembayaran_hutang.bank');

            if (!$akunHutangUsaha) {
                Log::error("Akun hutang usaha untuk jurnal pembayaran hutang belum dikonfigurasi", [
                    'pembayaran_id' => $this->id,
                    'nomor' => $this->nomor
                ]);
                return false;
            }

            // Menyiapkan entri jurnal
            $entries = [];

            // Debit: Kas atau Bank tergantung metode pembayaran (asset berkurang)
            $isKas = in_array(strtolower($this->metode_pembayaran), ['kas', 'tunai']);

            if ($isKas) {
                // Jika ada kas_id, cari akun akuntansi yang terkait dengan kas tersebut
                if ($this->kas_id) {
                    $akunKas = \App\Models\AkunAkuntansi::where('ref_type', 'App\Models\Kas')
                        ->where('ref_id', $this->kas_id)
                        ->first();
                    $akunSumber = $akunKas ? $akunKas->id : $akunKasDefault;
                } else {
                    $akunSumber = $akunKasDefault;
                }
            } else {
                // Untuk pembayaran bank
                if ($this->rekening_id) {
                    // Cari akun akuntansi yang terkait dengan rekening bank yang dipilih
                    $akunBank = \App\Models\AkunAkuntansi::where('ref_type', 'App\Models\RekeningBank')
                        ->where('ref_id', $this->rekening_id)
                        ->first();
                    $akunSumber = $akunBank ? $akunBank->id : $akunBankDefault;
                } else {
                    $akunSumber = $akunBankDefault;
                }
            }

            if (!$akunSumber) {
                Log::error("Akun sumber pembayaran tidak ditemukan", [
                    'pembayaran_id' => $this->id,
                    'nomor' => $this->nomor,
                    'metode_pembayaran' => $this->metode_pembayaran,
                    'kas_id' => $this->kas_id,
                    'rekening_id' => $this->rekening_id
                ]);
                return false;
            }

            // Debit: Hutang Usaha (liability berkurang)
            $entries[] = [
                'akun_id' => $akunHutangUsaha,
                'debit' => $this->jumlah,
                'kredit' => 0
            ];

            // Kredit: Kas atau Bank (asset berkurang)
            $entries[] = [
                'akun_id' => $akunSumber,
                'debit' => 0,
                'kredit' => $this->jumlah
            ];

            // Buat jurnal otomatis dengan sinkronisasi saldo
            $this->createJournalEntries(
                $entries,
                $this->nomor,
                "Pembayaran Hutang: {$this->nomor}",
                $this->tanggal
            );

            return true;
        } catch (\Exception $e) {
            Log::error("Error saat membuat jurnal otomatis untuk pembayaran hutang: " . $e->getMessage(), [
                'exception' => $e,
                'pembayaran_id' => $this->id,
                'nomor' => $this->nomor
            ]);
            return false;
        }
    }
}
