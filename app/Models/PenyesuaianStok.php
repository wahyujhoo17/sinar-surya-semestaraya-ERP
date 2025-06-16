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
            // Mendapatkan ID akun dari konfigurasi
            $akunPersediaan = config('accounting.penyesuaian_stok.persediaan');
            $akunPenyesuaianPersediaan = config('accounting.penyesuaian_stok.penyesuaian_persediaan');

            if (!$akunPersediaan || !$akunPenyesuaianPersediaan) {
                Log::error("Akun untuk jurnal penyesuaian stok belum dikonfigurasi", [
                    'penyesuaian_id' => $this->id,
                    'nomor' => $this->nomor
                ]);
                return false;
            }

            // Menyiapkan entri jurnal
            $entries = [];

            // Hitung total nilai penyesuaian
            $totalSelisihNilai = 0;
            foreach ($this->details as $detail) {
                $produk = $detail->produk;
                if ($produk) {
                    $hargaPokok = $produk->harga_pokok ?? 0;
                    $selisihQty = $detail->qty_baru - $detail->qty_lama;
                    $selisihNilai = $selisihQty * $hargaPokok;
                    $totalSelisihNilai += $selisihNilai;
                }
            }

            // Jika ada selisih nilai
            if (abs($totalSelisihNilai) > 0.01) {
                if ($totalSelisihNilai > 0) {
                    // Penambahan stok
                    $entries[] = [
                        'akun_id' => $akunPersediaan,
                        'debit' => $totalSelisihNilai,
                        'kredit' => 0
                    ];

                    $entries[] = [
                        'akun_id' => $akunPenyesuaianPersediaan,
                        'debit' => 0,
                        'kredit' => $totalSelisihNilai
                    ];
                } else {
                    // Pengurangan stok
                    $entries[] = [
                        'akun_id' => $akunPenyesuaianPersediaan,
                        'debit' => abs($totalSelisihNilai),
                        'kredit' => 0
                    ];

                    $entries[] = [
                        'akun_id' => $akunPersediaan,
                        'debit' => 0,
                        'kredit' => abs($totalSelisihNilai)
                    ];
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
                "Penyesuaian Stok: {$this->nomor}",
                $this->tanggal,
                $this
            );
        } catch (\Exception $e) {
            Log::error("Error saat membuat jurnal otomatis untuk penyesuaian stok: " . $e->getMessage(), [
                'exception' => $e,
                'penyesuaian_id' => $this->id,
                'nomor' => $this->nomor
            ]);
            return false;
        }
    }
}
