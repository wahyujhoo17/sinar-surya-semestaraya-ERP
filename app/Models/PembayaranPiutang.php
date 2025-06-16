<?php

namespace App\Models;

use App\Services\JournalEntryService;
use App\Traits\AutomaticJournalEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class PembayaranPiutang extends Model
{
    use HasFactory, AutomaticJournalEntry;

    protected $table = 'pembayaran_piutang';

    protected $fillable = [
        'nomor',
        'tanggal',
        'invoice_id',
        'customer_id',
        'jumlah',
        'metode_pembayaran',
        'no_referensi',
        'catatan',
        'user_id',
        'kas_id',
        'rekening_bank_id'
    ];

    /**
     * Relasi ke Invoice
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
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
     * Relasi ke Kas
     */
    public function kas(): BelongsTo
    {
        return $this->belongsTo(Kas::class, 'kas_id');
    }

    /**
     * Relasi ke Rekening Bank
     */
    public function rekeningBank(): BelongsTo
    {
        return $this->belongsTo(RekeningBank::class, 'rekening_bank_id');
    }

    /**
     * Get the bank transaction associated with the payment.
     */
    public function transaksiBank(): MorphOne
    {
        return $this->morphOne(TransaksiBank::class, 'related');
    }

    /**
     * Get the cash transaction associated with the payment.
     */
    public function transaksiKas(): MorphOne
    {
        return $this->morphOne(TransaksiKas::class, 'related');
    }

    /**
     * Membuat jurnal otomatis saat pembayaran piutang dicatat
     */
    public function createAutomaticJournal()
    {
        try {
            // Mendapatkan ID akun dari konfigurasi
            $akunPiutangUsaha = config('accounting.pembayaran_piutang.piutang_usaha');
            $akunKas = config('accounting.pembayaran_piutang.kas');
            $akunBank = config('accounting.pembayaran_piutang.bank');

            if (!$akunPiutangUsaha || (!$akunKas && !$akunBank)) {
                Log::error("Akun untuk jurnal pembayaran piutang belum dikonfigurasi", [
                    'pembayaran_id' => $this->id,
                    'nomor' => $this->nomor
                ]);
                return false;
            }

            // Menyiapkan entri jurnal
            $entries = [];

            // Debit: Kas atau Bank, tergantung metode pembayaran
            if ($this->metode_pembayaran == 'kas' && $akunKas) {
                $entries[] = [
                    'akun_id' => $akunKas,
                    'debit' => $this->jumlah,
                    'kredit' => 0
                ];
            } elseif (($this->metode_pembayaran == 'transfer' || $this->metode_pembayaran == 'bank') && $akunBank) {
                $entries[] = [
                    'akun_id' => $akunBank,
                    'debit' => $this->jumlah,
                    'kredit' => 0
                ];
            } else {
                // Jika metode pembayaran tidak dikenali, gunakan Kas sebagai default
                $entries[] = [
                    'akun_id' => $akunKas,
                    'debit' => $this->jumlah,
                    'kredit' => 0
                ];
            }

            // Kredit: Piutang Usaha
            $entries[] = [
                'akun_id' => $akunPiutangUsaha,
                'debit' => 0,
                'kredit' => $this->jumlah
            ];

            // Buat jurnal otomatis
            $service = new JournalEntryService();
            return $service->createJournalEntries(
                $entries,
                $this->nomor,
                "Penerimaan pembayaran piutang: {$this->nomor} dari {$this->customer->nama}",
                $this->tanggal,
                $this
            );
        } catch (\Exception $e) {
            Log::error("Error saat membuat jurnal otomatis untuk pembayaran piutang: " . $e->getMessage(), [
                'exception' => $e,
                'pembayaran_id' => $this->id,
                'nomor' => $this->nomor
            ]);
            return false;
        }
    }
}
