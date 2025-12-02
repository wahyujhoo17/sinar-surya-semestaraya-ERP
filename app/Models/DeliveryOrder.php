<?php

namespace App\Models;

use App\Traits\AutomaticJournalEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class DeliveryOrder extends Model
{
    use HasFactory, AutomaticJournalEntry;

    protected $table = 'delivery_order';

    protected $fillable = [
        'nomor',
        'tanggal',
        'sales_order_id',
        'customer_id',
        'user_id',
        'gudang_id',
        'permintaan_barang_id',
        'alamat_pengiriman',
        'status', // 'draft', 'dikirim', 'diterima', 'dibatalkan'
        'catatan',
        'keterangan_penerima',
        'nama_penerima',
        'tanggal_diterima'
    ];

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
     * Relasi ke Gudang
     */
    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }

    /**
     * Relasi ke Permintaan Barang
     */
    public function permintaanBarang()
    {
        return $this->belongsTo(PermintaanBarang::class, 'permintaan_barang_id');
    }

    /**
     * Relasi ke Detail Delivery Order
     */
    public function details()
    {
        return $this->hasMany(DeliveryOrderDetail::class, 'delivery_id');
    }

    /**
     * Accessor for backward compatibility with existing views
     */
    public function getDeliveryOrderDetailAttribute()
    {
        return $this->details;
    }

    /**
     * Membuat jurnal HPP otomatis saat delivery order dibuat/dikirim
     * Mengurangi persediaan dan mencatat Harga Pokok Penjualan
     */
    public function createAutomaticJournal()
    {
        try {
            // Get akun configuration dari database
            $akunPersediaan = \App\Models\AccountingConfiguration::get('laporan_keuangan.persediaan')
                ?? config('accounting.laporan_keuangan.persediaan');

            // Get HPP account - check multiple possible configurations
            $akunHpp = \App\Models\AccountingConfiguration::where('transaction_type', 'hpp')
                ->first();

            if (!$akunHpp) {
                // Fallback: cari akun HPP/Beban Pokok Penjualan
                $akunHppModel = \App\Models\AkunAkuntansi::where('kategori', 'expense')
                    ->where('is_active', true)
                    ->where(function ($q) {
                        $q->where('nama', 'LIKE', '%harga pokok%')
                            ->orWhere('nama', 'LIKE', '%hpp%')
                            ->orWhere('nama', 'LIKE', '%cost of goods%')
                            ->orWhere('nama', 'LIKE', '%beban pokok%')
                            ->orWhere('kode', 'LIKE', '50%');
                    })
                    ->first();

                if ($akunHppModel) {
                    $akunHpp = $akunHppModel->id;
                } else {
                    Log::warning("Akun HPP tidak ditemukan untuk delivery order: {$this->nomor}");
                    return false;
                }
            } else {
                $akunHpp = $akunHpp->akun_id;
            }

            if (!$akunPersediaan || !$akunHpp) {
                Log::error("Akun untuk jurnal HPP belum dikonfigurasi", [
                    'delivery_order_id' => $this->id,
                    'nomor' => $this->nomor,
                    'akun_persediaan' => $akunPersediaan,
                    'akun_hpp' => $akunHpp
                ]);
                return false;
            }

            // Calculate total HPP from delivery order items
            $totalHpp = 0;
            $details = $this->details()->with('produk')->get();

            foreach ($details as $detail) {
                if ($detail->produk) {
                    // Use harga_beli_rata_rata if available, fallback to harga_beli
                    $hargaBeli = $detail->produk->harga_beli_rata_rata ?? $detail->produk->harga_beli ?? 0;
                    $hpp = $hargaBeli * $detail->quantity;
                    $totalHpp += $hpp;

                    Log::info("HPP Calculation for DO {$this->nomor}", [
                        'produk' => $detail->produk->nama,
                        'quantity' => $detail->quantity,
                        'harga_beli_rata_rata' => $detail->produk->harga_beli_rata_rata ?? null,
                        'harga_beli' => $detail->produk->harga_beli,
                        'harga_used' => $hargaBeli,
                        'hpp' => $hpp
                    ]);
                }
            }

            // Skip if no HPP to record
            if ($totalHpp <= 0) {
                Log::info("No HPP to record for delivery order: {$this->nomor}");
                return true;
            }

            // Create journal entries
            $entries = [];

            // DEBIT: HPP (Beban Pokok Penjualan)
            $entries[] = [
                'akun_id' => $akunHpp,
                'debit' => $totalHpp,
                'kredit' => 0
            ];

            // KREDIT: Persediaan
            $entries[] = [
                'akun_id' => $akunPersediaan,
                'debit' => 0,
                'kredit' => $totalHpp
            ];

            // Use tanggal_diterima if available (when DO completed), otherwise use tanggal DO
            $tanggalJurnal = $this->tanggal_diterima ?? now()->format('Y-m-d');

            // Create journal with AutomaticJournalEntry trait
            $this->createJournalEntries(
                $entries,
                $this->nomor,
                "HPP untuk delivery order: {$this->nomor}",
                $tanggalJurnal
            );

            Log::info("HPP Journal created successfully", [
                'delivery_order' => $this->nomor,
                'total_hpp' => $totalHpp,
                'tanggal_jurnal' => $tanggalJurnal,
                'tanggal_do' => $this->tanggal,
                'tanggal_diterima' => $this->tanggal_diterima
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("Error creating HPP journal for delivery order: " . $e->getMessage(), [
                'exception' => $e,
                'delivery_order_id' => $this->id,
                'nomor' => $this->nomor
            ]);
            return false;
        }
    }
}
