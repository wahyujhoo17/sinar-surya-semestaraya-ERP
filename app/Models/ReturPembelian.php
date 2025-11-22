<?php

namespace App\Models;

use App\Services\JournalEntryService;
use App\Traits\AutomaticJournalEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ReturPembelian extends Model
{
    use HasFactory, AutomaticJournalEntry;

    protected $table = 'retur_pembelian';

    protected $fillable = [
        'nomor',
        'tanggal',
        'purchase_order_id',
        'supplier_id',
        'user_id',
        'catatan',
        'status', // 'draft', 'diproses', 'menunggu_barang_pengganti', 'selesai'
        'tipe_retur' // 'pengembalian_dana', 'tukar_barang'
    ];

    protected $casts = [
        'tanggal' => 'datetime',
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
     * Relasi ke Detail Retur Pembelian
     */
    public function details()
    {
        return $this->hasMany(ReturPembelianDetail::class, 'retur_id');
    }

    /**
     * Membuat jurnal otomatis saat retur pembelian dibuat
     */
    public function createAutomaticJournal()
    {
        try {
            // Mendapatkan ID akun dari konfigurasi database (fallback ke config file)
            $akunHutangUsaha = \App\Models\AccountingConfiguration::get('retur_pembelian.hutang_usaha')
                ?? config('accounting.retur_pembelian.hutang_usaha');
            $akunPersediaan = \App\Models\AccountingConfiguration::get('retur_pembelian.persediaan')
                ?? config('accounting.retur_pembelian.persediaan');
            $akunPpnMasukan = \App\Models\AccountingConfiguration::get('retur_pembelian.ppn_masukan')
                ?? config('accounting.retur_pembelian.ppn_masukan');

            if (!$akunHutangUsaha || !$akunPersediaan) {
                Log::error("Akun untuk jurnal retur pembelian belum dikonfigurasi", [
                    'retur_id' => $this->id,
                    'nomor' => $this->nomor
                ]);
                return false;
            }

            // Menyiapkan entri jurnal
            $entries = [];

            // Jika tipe retur adalah pengembalian dana
            if ($this->tipe_retur == 'pengembalian_dana' && ($this->status == 'diproses' || $this->status == 'selesai')) {
                // Hitung total nilai retur
                $totalRetur = 0;
                foreach ($this->details as $detail) {
                    $totalRetur += $detail->harga * $detail->qty;
                }

                // Hitung PPN jika ada
                $subtotal = $totalRetur / 1.11; // Asumsi PPN 11%, sesuaikan jika berbeda
                $ppnAmount = $totalRetur - $subtotal;

                // Debit: Hutang Usaha (Mengurangi hutang)
                $entries[] = [
                    'akun_id' => $akunHutangUsaha,
                    'debit' => $totalRetur,
                    'kredit' => 0
                ];

                // Kredit: Persediaan (Mengurangi nilai persediaan)
                $entries[] = [
                    'akun_id' => $akunPersediaan,
                    'debit' => 0,
                    'kredit' => $subtotal
                ];

                // Kredit: PPN Masukan (jika ada)
                if ($ppnAmount > 0 && $akunPpnMasukan) {
                    $entries[] = [
                        'akun_id' => $akunPpnMasukan,
                        'debit' => 0,
                        'kredit' => $ppnAmount
                    ];
                }
            }
            // Jika tipe retur adalah tukar barang, mungkin tidak perlu jurnal jika nilainya sama
            else if ($this->tipe_retur == 'tukar_barang' && ($this->status == 'diproses' || $this->status == 'selesai')) {
                // Untuk pertukaran barang dengan nilai yang sama, secara akuntansi tidak ada perubahan
                // Namun jika ada selisih nilai, perlu dibuat jurnal untuk selisih tersebut
                // Implementasi logika untuk menghitung selisih nilai jika diperlukan
            }

            // Jika tidak ada entri jurnal yang dibuat, lewati
            if (empty($entries)) {
                return true;
            }

            // Buat jurnal otomatis
            $service = new JournalEntryService();
            return $service->createJournalEntries(
                $entries,
                $this->nomor,
                "Retur Pembelian: {$this->nomor}",
                $this->tanggal,
                $this
            );
        } catch (\Exception $e) {
            Log::error("Error saat membuat jurnal otomatis untuk retur pembelian: " . $e->getMessage(), [
                'exception' => $e,
                'retur_id' => $this->id,
                'nomor' => $this->nomor
            ]);
            return false;
        }
    }
}
