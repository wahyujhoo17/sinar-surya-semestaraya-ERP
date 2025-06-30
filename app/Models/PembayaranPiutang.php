<?php

namespace App\Models;

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
            $akunKasDefault = config('accounting.pembayaran_piutang.kas');
            $akunBankDefault = config('accounting.pembayaran_piutang.bank');

            if (!$akunPiutangUsaha) {
                Log::error("Akun piutang usaha untuk jurnal pembayaran piutang belum dikonfigurasi", [
                    'pembayaran_id' => $this->id,
                    'nomor' => $this->nomor
                ]);
                return false;
            }

            // Menyiapkan entri jurnal
            $entries = [];

            // Debit: Kas atau Bank, tergantung metode pembayaran dan akun yang dipilih
            $akunSumber = null;

            if ($this->metode_pembayaran == 'Kas' || $this->metode_pembayaran == 'kas' || $this->metode_pembayaran == 'tunai') {
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
                // Untuk pembayaran bank (Bank Transfer, transfer, bank)
                if ($this->rekening_bank_id) {
                    // Cari akun akuntansi yang terkait dengan rekening bank yang dipilih
                    $akunBank = \App\Models\AkunAkuntansi::where('ref_type', 'App\Models\RekeningBank')
                        ->where('ref_id', $this->rekening_bank_id)
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
                    'rekening_bank_id' => $this->rekening_bank_id
                ]);
                return false;
            }

            $entries[] = [
                'akun_id' => $akunSumber,
                'debit' => $this->jumlah,
                'kredit' => 0
            ];

            // Kredit: Piutang Usaha
            $entries[] = [
                'akun_id' => $akunPiutangUsaha,
                'debit' => 0,
                'kredit' => $this->jumlah
            ];

            // Buat jurnal otomatis dengan sinkronisasi saldo
            $this->createJournalEntries(
                $entries,
                $this->nomor,
                "Penerimaan pembayaran piutang: {$this->nomor} dari {$this->customer->nama}",
                $this->tanggal
            );

            return true;
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
