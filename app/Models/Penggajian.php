<?php

namespace App\Models;

use App\Traits\AutomaticJournalEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Penggajian extends Model
{
    use HasFactory, AutomaticJournalEntry;

    protected $table = 'penggajian';

    protected $fillable = [
        'karyawan_id',
        'bulan',
        'tahun',
        'gaji_pokok',
        'tunjangan',
        'bonus',
        'lembur',
        'potongan',
        'total_gaji',
        'tanggal_bayar',
        'metode_pembayaran', // 'kas', 'bank'
        'kas_id',
        'rekening_id',
        'status', // 'draft', 'disetujui', 'dibayar'
        'catatan',
        'disetujui_oleh'
    ];

    /**
     * Relasi ke Karyawan
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    /**
     * Relasi ke User yang menyetujui
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    /**
     * Relasi ke Detail Komponen Gaji
     */
    public function komponenGaji()
    {
        return $this->hasMany(KomponenGaji::class, 'penggajian_id');
    }

    /**
     * Relasi ke Kas
     */
    public function kas()
    {
        return $this->belongsTo(Kas::class, 'kas_id');
    }

    /**
     * Relasi ke Rekening Bank
     */
    public function rekeningBank()
    {
        return $this->belongsTo(RekeningBank::class, 'rekening_id');
    }

    /**
     * Membuat jurnal otomatis saat penggajian dibayar
     */
    public function createAutomaticJournal()
    {
        try {
            // Hanya buat jurnal jika status adalah 'dibayar'
            if ($this->status !== 'dibayar') {
                return false;
            }

            // Mendapatkan ID akun dari konfigurasi
            $akunBebanGaji = config('accounting.penggajian.beban_gaji');

            if (!$akunBebanGaji) {
                Log::error("Akun beban gaji untuk jurnal penggajian belum dikonfigurasi", [
                    'penggajian_id' => $this->id,
                    'karyawan_id' => $this->karyawan_id
                ]);
                return false;
            }

            // Menyiapkan entri jurnal
            $entries = [];

            // Debit: Beban Gaji
            $entries[] = [
                'akun_id' => $akunBebanGaji,
                'debit' => $this->total_gaji,
                'kredit' => 0
            ];

            // Tentukan akun sumber berdasarkan metode pembayaran
            $akunSumber = null;
            $metodePembayaran = $this->metode_pembayaran ?: 'kas';

            if ($metodePembayaran === 'bank' && $this->rekening_id) {
                // Cari akun akuntansi yang terkait dengan rekening bank
                $akunBank = \App\Models\AkunAkuntansi::where('ref_type', 'App\Models\RekeningBank')
                    ->where('ref_id', $this->rekening_id)
                    ->first();

                if ($akunBank) {
                    $akunSumber = $akunBank->id;
                } else {
                    Log::warning("Akun akuntansi untuk rekening bank ID {$this->rekening_id} tidak ditemukan", [
                        'penggajian_id' => $this->id,
                        'rekening_id' => $this->rekening_id
                    ]);
                }
            } elseif ($metodePembayaran === 'kas' && $this->kas_id) {
                // Cari akun akuntansi yang terkait dengan kas
                $akunKas = \App\Models\AkunAkuntansi::where('ref_type', 'App\Models\Kas')
                    ->where('ref_id', $this->kas_id)
                    ->first();

                if ($akunKas) {
                    $akunSumber = $akunKas->id;
                } else {
                    Log::warning("Akun akuntansi untuk kas ID {$this->kas_id} tidak ditemukan", [
                        'penggajian_id' => $this->id,
                        'kas_id' => $this->kas_id
                    ]);
                }
            }

            // Fallback ke akun default jika tidak ada akun spesifik
            if (!$akunSumber) {
                $akunKasDefault = config('accounting.penggajian.kas');
                $akunBankDefault = config('accounting.penggajian.bank');
                $akunSumber = $akunKasDefault ?: $akunBankDefault;

                Log::info("Menggunakan akun default untuk penggajian", [
                    'penggajian_id' => $this->id,
                    'akun_default' => $akunSumber,
                    'metode_pembayaran' => $metodePembayaran
                ]);
            }

            if (!$akunSumber) {
                Log::error("Akun kas/bank untuk jurnal penggajian belum dikonfigurasi", [
                    'penggajian_id' => $this->id,
                    'karyawan_id' => $this->karyawan_id
                ]);
                return false;
            }

            $entries[] = [
                'akun_id' => $akunSumber,
                'debit' => 0,
                'kredit' => $this->total_gaji
            ];

            // Buat jurnal otomatis
            $noReferensi = 'GAJI-' . $this->id . '-' . $this->bulan . '/' . $this->tahun;
            $keterangan = 'Pembayaran gaji ' . $this->karyawan->nama . ' bulan ' . $this->bulan . '/' . $this->tahun;

            return $this->createJournalEntries(
                $entries,
                $noReferensi,
                $keterangan,
                $this->tanggal_bayar
            );
        } catch (\Exception $e) {
            Log::error("Error saat membuat jurnal penggajian: " . $e->getMessage(), [
                'penggajian_id' => $this->id,
                'exception' => $e
            ]);
            return false;
        }
    }
}
