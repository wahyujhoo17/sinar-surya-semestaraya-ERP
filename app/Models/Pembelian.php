<?php

namespace App\Models;

use App\Traits\AutomaticJournalEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Pembelian extends Model
{
    use HasFactory, AutomaticJournalEntry;

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
        'status',
        'status_pembayaran',
        'status_penerimaan',
        'tanggal_pengiriman',
        'alamat_pengiriman',
        'catatan',
        'syarat_ketentuan'
    ];

    /**
     * Get the supplier associated with the purchase
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the user who created the purchase
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the purchase details
     */
    public function detail()
    {
        return $this->hasMany(PembelianDetail::class, 'po_id');
    }

    /**
     * Accessor for nomor_faktur to alias the nomor field
     */
    public function getNomorFakturAttribute()
    {
        return $this->nomor;
    }

    /**
     * Accessor for total_bayar to get payment total
     */
    public function getTotalBayarAttribute()
    {
        return $this->pembayaran()->sum('jumlah') ?? 0;
    }

    /**
     * Relation to payments
     */
    public function pembayaran()
    {
        return $this->hasMany(PembayaranHutang::class, 'purchase_order_id');
    }

    /**
     * Membuat jurnal otomatis saat pembelian dibuat
     */
    public function createAutomaticJournal()
    {
        try {
            // Mendapatkan ID akun dari konfigurasi
            $akunHutangUsaha = config('accounting.pembelian.hutang_usaha');
            $akunPersediaan = config('accounting.pembelian.persediaan');
            $akunPpnMasukan = config('accounting.pembelian.ppn_masukan');

            if (!$akunHutangUsaha || !$akunPersediaan) {
                Log::error("Akun untuk jurnal pembelian belum dikonfigurasi", [
                    'purchase_id' => $this->id,
                    'nomor' => $this->nomor
                ]);
                return false;
            }

            // Menyiapkan entri jurnal
            $entries = [];

            // Debit: Persediaan Barang (Total tanpa PPN)
            $taxRate = (float) setting('tax_percentage', 11) / 100;
            $totalBeforeTax = $this->total / (1 + $taxRate); // Dynamic tax rate from settings

            $entries[] = [
                'akun_id' => $akunPersediaan,
                'debit' => $totalBeforeTax,
                'kredit' => 0
            ];

            // Debit: PPN Masukan (jika ada)
            $ppnAmount = $this->total - $totalBeforeTax;
            if ($ppnAmount > 0 && $akunPpnMasukan) {
                $entries[] = [
                    'akun_id' => $akunPpnMasukan,
                    'debit' => $ppnAmount,
                    'kredit' => 0
                ];
            }

            // Kredit: Hutang Usaha
            $entries[] = [
                'akun_id' => $akunHutangUsaha,
                'debit' => 0,
                'kredit' => $this->total
            ];

            // Buat jurnal otomatis dengan sinkronisasi saldo
            $this->createJournalEntries(
                $entries,
                $this->nomor,
                "Pembelian: {$this->nomor}",
                $this->tanggal
            );

            return true;
        } catch (\Exception $e) {
            Log::error("Error saat membuat jurnal otomatis untuk pembelian: " . $e->getMessage(), [
                'exception' => $e,
                'purchase_id' => $this->id,
                'nomor' => $this->nomor
            ]);
            return false;
        }
    }
}
