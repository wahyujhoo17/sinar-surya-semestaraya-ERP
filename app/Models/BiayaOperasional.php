<?php

namespace App\Models;

use App\Traits\AutomaticJournalEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class BiayaOperasional extends Model
{
    use HasFactory, AutomaticJournalEntry;

    protected $table = 'biaya_operasional';

    protected $fillable = [
        'nomor',
        'tanggal',
        'kategori_biaya_id',
        'jumlah',
        'metode_pembayaran',
        'no_referensi',
        'keterangan',
        'user_id',
        'bukti_transaksi'
    ];

    /**
     * Relasi ke Kategori Biaya
     */
    public function kategoriBiaya()
    {
        return $this->belongsTo(KategoriBiaya::class, 'kategori_biaya_id');
    }

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the cash transaction associated with this expense
     */
    public function transaksiKas()
    {
        return $this->morphOne(TransaksiKas::class, 'related');
    }

    /**
     * Get the bank transaction associated with this expense
     */
    public function transaksiBank()
    {
        return $this->morphOne(TransaksiBank::class, 'related');
    }

    /**
     * Membuat jurnal otomatis saat biaya operasional dibuat
     */
    public function createAutomaticJournal()
    {
        try {
            // Mendapatkan ID akun dari konfigurasi
            $akunKas = config('accounting.beban_operasional.kas');
            $akunBank = config('accounting.beban_operasional.bank');

            // Mendapatkan akun beban berdasarkan kategori
            $kategori = $this->kategoriBiaya ? strtolower(str_replace(' ', '_', $this->kategoriBiaya->nama)) : 'lainnya';
            $akunBeban = config("accounting.beban_operasional.beban_{$kategori}");

            // Jika tidak ada akun beban spesifik, gunakan akun beban lainnya
            if (!$akunBeban) {
                $akunBeban = config('accounting.beban_operasional.beban_lainnya');
            }

            if (!$akunBeban || (!$akunKas && !$akunBank)) {
                Log::error("Akun untuk jurnal biaya operasional belum dikonfigurasi", [
                    'biaya_id' => $this->id,
                    'nomor' => $this->nomor,
                    'kategori' => $kategori
                ]);
                return false;
            }

            // Menyiapkan entri jurnal
            $entries = [];

            // Debit: Beban Operasional
            $entries[] = [
                'akun_id' => $akunBeban,
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

            // Buat jurnal otomatis dengan sinkronisasi saldo
            $this->createJournalEntries(
                $entries,
                $this->nomor,
                "Biaya Operasional: {$this->nomor} - {$this->keterangan}",
                $this->tanggal
            );

            return true;
        } catch (\Exception $e) {
            Log::error("Error saat membuat jurnal otomatis untuk biaya operasional: " . $e->getMessage(), [
                'exception' => $e,
                'biaya_id' => $this->id,
                'nomor' => $this->nomor
            ]);
            return false;
        }
    }
}
