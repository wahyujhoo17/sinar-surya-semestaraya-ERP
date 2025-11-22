<?php

namespace App\Models;

use App\Services\JournalEntryService;
use App\Traits\AutomaticJournalEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ReturPenjualan extends Model
{
    use HasFactory, AutomaticJournalEntry;

    protected $table = 'retur_penjualan';

    protected $fillable = [
        'nomor',
        'tanggal',
        'sales_order_id',
        'customer_id',
        'user_id',
        'catatan',
        'total',
        'status', // 'draft', 'menunggu_persetujuan', 'disetujui', 'ditolak', 'diproses', 'menunggu_pengiriman', 'menunggu_barang_pengganti', 'selesai'
        'tipe_retur', // 'pengembalian_dana', 'tukar_barang'
        'requires_qc',
        'qc_passed',
        'qc_notes',
        'qc_by_user_id',
        'qc_at'
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
     * Relasi ke Detail Retur Penjualan
     */
    public function details()
    {
        return $this->hasMany(ReturPenjualanDetail::class, 'retur_id');
    }

    /**
     * Relasi ke User yang melakukan QC
     */
    public function qcByUser()
    {
        return $this->belongsTo(User::class, 'qc_by_user_id');
    }

    /**
     * Relasi ke Nota Kredit
     */
    public function notaKredit()
    {
        return $this->hasOne(NotaKredit::class, 'retur_penjualan_id');
    }

    /**
     * Relasi ke Item Pengganti
     */
    public function barangPengganti()
    {
        return $this->hasMany(ReturPenjualanPengganti::class, 'retur_id');
    }

    /**
     * Membuat jurnal otomatis saat retur penjualan dibuat
     */
    public function createAutomaticJournal()
    {
        try {
            // Mendapatkan ID akun dari konfigurasi database (fallback ke config file)
            $akunPiutangUsaha = \App\Models\AccountingConfiguration::get('retur_penjualan.piutang_usaha')
                ?? config('accounting.retur_penjualan.piutang_usaha');
            $akunPendapatanPenjualan = \App\Models\AccountingConfiguration::get('retur_penjualan.pendapatan_penjualan')
                ?? config('accounting.retur_penjualan.pendapatan_penjualan');
            $akunPersediaan = \App\Models\AccountingConfiguration::get('retur_penjualan.persediaan')
                ?? config('accounting.retur_penjualan.persediaan');
            $akunHpp = \App\Models\AccountingConfiguration::get('retur_penjualan.hpp')
                ?? config('accounting.retur_penjualan.hpp');
            $akunPpnKeluaran = \App\Models\AccountingConfiguration::get('retur_penjualan.ppn_keluaran')
                ?? config('accounting.retur_penjualan.ppn_keluaran');

            if (!$akunPiutangUsaha || !$akunPendapatanPenjualan || !$akunPersediaan || !$akunHpp) {
                Log::error("Akun untuk jurnal retur penjualan belum dikonfigurasi", [
                    'retur_id' => $this->id,
                    'nomor' => $this->nomor
                ]);
                return false;
            }

            // Menyiapkan entri jurnal
            $entries = [];

            // Jika tipe retur adalah pengembalian dana
            if ($this->tipe_retur == 'pengembalian_dana') {
                // Debit: Pendapatan Penjualan (Mengurangi pendapatan)
                $subtotal = $this->total / 1.11; // Asumsi PPN 11%, sesuaikan jika berbeda
                $entries[] = [
                    'akun_id' => $akunPendapatanPenjualan,
                    'debit' => $subtotal,
                    'kredit' => 0
                ];

                // Debit: PPN Keluaran (jika ada)
                $ppnAmount = $this->total - $subtotal;
                if ($ppnAmount > 0 && $akunPpnKeluaran) {
                    $entries[] = [
                        'akun_id' => $akunPpnKeluaran,
                        'debit' => $ppnAmount,
                        'kredit' => 0
                    ];
                }

                // Kredit: Piutang Usaha (Mengurangi piutang)
                $entries[] = [
                    'akun_id' => $akunPiutangUsaha,
                    'debit' => 0,
                    'kredit' => $this->total
                ];

                // Jika barang dikembalikan, perlu entri untuk persediaan
                if ($this->status == 'disetujui' || $this->status == 'selesai') {
                    // Debit: Persediaan (Barang kembali ke stock)
                    $hpp = 0;
                    foreach ($this->details as $detail) {
                        $produk = $detail->produk;
                        if ($produk) {
                            $hpp += $produk->harga_pokok * $detail->qty;
                        }
                    }

                    $entries[] = [
                        'akun_id' => $akunPersediaan,
                        'debit' => $hpp,
                        'kredit' => 0
                    ];

                    // Kredit: Harga Pokok Penjualan (Mengurangi HPP)
                    $entries[] = [
                        'akun_id' => $akunHpp,
                        'debit' => 0,
                        'kredit' => $hpp
                    ];
                }
            }
            // Jika tipe retur adalah tukar barang, hanya jurnal untuk persediaan yang perlu diubah
            else if ($this->tipe_retur == 'tukar_barang' && ($this->status == 'disetujui' || $this->status == 'selesai')) {
                // Untuk barang yang dikembalikan dan barang pengganti, hanya perlu menyesuaikan selisih nilai
                $totalNilaiKembali = 0;
                $totalNilaiPengganti = 0;

                // Hitung nilai barang yang dikembalikan
                foreach ($this->details as $detail) {
                    $produk = $detail->produk;
                    if ($produk) {
                        $totalNilaiKembali += $produk->harga_pokok * $detail->qty;
                    }
                }

                // Hitung nilai barang pengganti
                foreach ($this->barangPengganti as $pengganti) {
                    $produk = $pengganti->produk;
                    if ($produk) {
                        $totalNilaiPengganti += $produk->harga_pokok * $pengganti->qty;
                    }
                }

                // Jika ada selisih nilai, buat jurnal untuk selisih tersebut
                $selisih = $totalNilaiPengganti - $totalNilaiKembali;

                if (abs($selisih) > 0.01) {
                    if ($selisih > 0) {
                        // Barang pengganti lebih mahal
                        $entries[] = [
                            'akun_id' => $akunHpp,
                            'debit' => $selisih,
                            'kredit' => 0
                        ];

                        $entries[] = [
                            'akun_id' => $akunPersediaan,
                            'debit' => 0,
                            'kredit' => $selisih
                        ];
                    } else {
                        // Barang kembali lebih mahal
                        $entries[] = [
                            'akun_id' => $akunPersediaan,
                            'debit' => abs($selisih),
                            'kredit' => 0
                        ];

                        $entries[] = [
                            'akun_id' => $akunHpp,
                            'debit' => 0,
                            'kredit' => abs($selisih)
                        ];
                    }
                }
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
                "Retur Penjualan: {$this->nomor}",
                $this->tanggal,
                $this
            );
        } catch (\Exception $e) {
            Log::error("Error saat membuat jurnal otomatis untuk retur penjualan: " . $e->getMessage(), [
                'exception' => $e,
                'retur_id' => $this->id,
                'nomor' => $this->nomor
            ]);
            return false;
        }
    }
}
