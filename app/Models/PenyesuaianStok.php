<?php

namespace App\Models;

use App\Services\JournalEntryService;
use App\Traits\AutomaticJournalEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class PenyesuaianStok extends Model
{
    use HasFactory, AutomaticJournalEntry;

    protected $table = 'penyesuaian_stok';

    protected $fillable = [
        'nomor',
        'tanggal',
        'gudang_id',
        'user_id',
        'catatan',
        'status' // 'draft', 'disetujui', 'selesai'
    ];

    /**
     * Relasi ke Gudang
     */
    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Detail Penyesuaian Stok
     */
    public function details()
    {
        return $this->hasMany(PenyesuaianStokDetail::class, 'penyesuaian_id');
    }

    /**
     * Membuat jurnal otomatis saat penyesuaian stok dibuat
     */
    public function createAutomaticJournal()
    {
        try {
            // Mendapatkan ID akun dari konfigurasi database (fallback ke config file)
            $akunPersediaan = \App\Models\AccountingConfiguration::where('transaction_type', 'penyesuaian_stok')
                ->where('account_key', 'persediaan')
                ->first();

            $akunPenyesuaianPersediaan = \App\Models\AccountingConfiguration::where('transaction_type', 'penyesuaian_stok')
                ->where('account_key', 'penyesuaian_persediaan')
                ->first();

            // Fallback to laporan_keuangan config if penyesuaian_stok not found
            if (!$akunPersediaan) {
                $akunPersediaan = \App\Models\AccountingConfiguration::where('transaction_type', 'laporan_keuangan')
                    ->where('account_key', 'persediaan')
                    ->first();
            }

            if (!$akunPersediaan || !$akunPenyesuaianPersediaan) {
                Log::error("Akun untuk jurnal penyesuaian stok belum dikonfigurasi", [
                    'penyesuaian_id' => $this->id,
                    'nomor' => $this->nomor,
                    'akun_persediaan' => $akunPersediaan ? $akunPersediaan->akun_id : null,
                    'akun_penyesuaian' => $akunPenyesuaianPersediaan ? $akunPenyesuaianPersediaan->akun_id : null
                ]);
                return false;
            }

            // Menyiapkan entri jurnal
            $entries = [];

            // Hitung total nilai penyesuaian
            $totalSelisihNilai = 0;
            $details = $this->details()->with('produk')->get();

            foreach ($details as $detail) {
                $produk = $detail->produk;
                if ($produk) {
                    // Use harga_beli_rata_rata if available, fallback to harga_beli, then harga_pokok
                    $hargaPokok = $produk->harga_beli_rata_rata
                        ?? $produk->harga_beli
                        ?? $produk->harga_pokok
                        ?? 0;

                    // PenyesuaianStokDetail uses: stok_tercatat, stok_fisik, selisih
                    $stokTercatat = $detail->stok_tercatat ?? 0;
                    $stokFisik = $detail->stok_fisik ?? 0;
                    $selisihQty = $detail->selisih ?? ($stokFisik - $stokTercatat);
                    $selisihNilai = $selisihQty * $hargaPokok;
                    $totalSelisihNilai += $selisihNilai;

                    Log::info("Penyesuaian Stok Calculation", [
                        'nomor' => $this->nomor,
                        'produk' => $produk->nama,
                        'stok_tercatat' => $stokTercatat,
                        'stok_fisik' => $stokFisik,
                        'selisih_qty' => $selisihQty,
                        'harga_beli_rata_rata' => $produk->harga_beli_rata_rata ?? null,
                        'harga_beli' => $produk->harga_beli,
                        'harga_used' => $hargaPokok,
                        'selisih_nilai' => $selisihNilai
                    ]);
                }
            }

            // Jika ada selisih nilai
            if (abs($totalSelisihNilai) > 0.01) {
                if ($totalSelisihNilai > 0) {
                    // Penambahan stok: DEBIT Persediaan, KREDIT Penyesuaian
                    $entries[] = [
                        'akun_id' => $akunPersediaan->akun_id,
                        'debit' => $totalSelisihNilai,
                        'kredit' => 0
                    ];

                    $entries[] = [
                        'akun_id' => $akunPenyesuaianPersediaan->akun_id,
                        'debit' => 0,
                        'kredit' => $totalSelisihNilai
                    ];
                } else {
                    // Pengurangan stok: DEBIT Penyesuaian, KREDIT Persediaan
                    $entries[] = [
                        'akun_id' => $akunPenyesuaianPersediaan->akun_id,
                        'debit' => abs($totalSelisihNilai),
                        'kredit' => 0
                    ];

                    $entries[] = [
                        'akun_id' => $akunPersediaan->akun_id,
                        'debit' => 0,
                        'kredit' => abs($totalSelisihNilai)
                    ];
                }

                Log::info("Penyesuaian Stok Journal Created", [
                    'nomor' => $this->nomor,
                    'total_selisih' => $totalSelisihNilai,
                    'entries_count' => count($entries)
                ]);
            } else {
                Log::info("No journal needed for stock adjustment (zero value)", [
                    'nomor' => $this->nomor
                ]);
            }

            // Jika tidak ada entri jurnal yang dibuat, lewati
            if (empty($entries)) {
                return true;
            }

            // Buat jurnal otomatis dengan AutomaticJournalEntry trait
            $this->createJournalEntries(
                $entries,
                $this->nomor,
                "Penyesuaian Stok: {$this->nomor}",
                $this->tanggal
            );

            return true;
        } catch (\Exception $e) {
            Log::error("Error saat membuat jurnal otomatis untuk penyesuaian stok: " . $e->getMessage(), [
                'exception' => $e,
                'penyesuaian_id' => $this->id,
                'nomor' => $this->nomor,
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
}
