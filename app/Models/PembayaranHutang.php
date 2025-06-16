<?php

namespace App\Models;

use App\Services\JournalEntryService;
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
        'user_id'
    ];

    /**
     * Relasi ke Purchase Order
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
            // Mendapatkan ID akun dari konfigurasi
            $akunHutangUsaha = config('accounting.pembayaran_hutang.hutang_usaha');
            $akunKas = config('accounting.pembayaran_hutang.kas');
            $akunBank = config('accounting.pembayaran_hutang.bank');

            if (!$akunHutangUsaha || (!$akunKas && !$akunBank)) {
                Log::error("Akun untuk jurnal pembayaran hutang belum dikonfigurasi", [
                    'pembayaran_id' => $this->id,
                    'nomor' => $this->nomor
                ]);
                return false;
            }

            // Menyiapkan entri jurnal
            $entries = [];

            // Debit: Hutang Usaha
            $entries[] = [
                'akun_id' => $akunHutangUsaha,
                'debit' => $this->jumlah,
                'kredit' => 0
            ];

            // Kredit: Kas atau Bank tergantung metode pembayaran
            $akunSumber = $this->metode_pembayaran == 'tunai' ? $akunKas : $akunBank;

            $entries[] = [
                'akun_id' => $akunSumber,
                'debit' => 0,
                'kredit' => $this->jumlah
            ];

            // Buat jurnal otomatis
            $service = new JournalEntryService();
            return $service->createJournalEntries(
                $entries,
                $this->nomor,
                "Pembayaran Hutang: {$this->nomor}",
                $this->tanggal,
                $this
            );
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
