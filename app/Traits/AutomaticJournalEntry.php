<?php

namespace App\Traits;

use App\Models\JurnalUmum;
use App\Models\AkunAkuntansi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait AutomaticJournalEntry
{
    /**
     * Membuat entri jurnal umum secara otomatis.
     *
     * @param array $entries Array berisi entri jurnal yang akan dibuat
     * @param string $noReferensi Nomor referensi untuk entri jurnal
     * @param string $keterangan Keterangan transaksi
     * @param string|null $tanggal Tanggal transaksi, default adalah hari ini
     * @return void
     * 
     * Contoh penggunaan:
     * $entries = [
     *    ['akun_id' => 1, 'debit' => 1000000, 'kredit' => 0],
     *    ['akun_id' => 2, 'debit' => 0, 'kredit' => 1000000]
     * ];
     * $this->createJournalEntries($entries, 'INV-001', 'Pembayaran Invoice INV-001');
     */
    public function createJournalEntries(array $entries, string $noReferensi, string $keterangan, ?string $tanggal = null)
    {
        // Validasi bahwa total debit dan kredit harus sama
        $totalDebit = 0;
        $totalKredit = 0;

        foreach ($entries as $entry) {
            $totalDebit += (float) ($entry['debit'] ?? 0);
            $totalKredit += (float) ($entry['kredit'] ?? 0);
        }

        if (abs($totalDebit - $totalKredit) > 0.01) {
            throw new \Exception('Total debit dan kredit harus sama untuk entri jurnal. Debit: ' . $totalDebit . ', Kredit: ' . $totalKredit);
        }

        try {
            DB::beginTransaction();

            $tanggal = $tanggal ?? date('Y-m-d');
            $userId = Auth::id();

            // Buat entri jurnal untuk setiap akun yang terlibat
            foreach ($entries as $entry) {
                JurnalUmum::create([
                    'tanggal' => $tanggal,
                    'no_referensi' => $noReferensi,
                    'akun_id' => $entry['akun_id'],
                    'debit' => $entry['debit'] ?? 0,
                    'kredit' => $entry['kredit'] ?? 0,
                    'keterangan' => $keterangan,
                    'user_id' => $userId,
                    'ref_type' => get_class($this),
                    'ref_id' => $this->id
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Dapatkan entri jurnal terkait dengan model ini.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function journalEntries()
    {
        return $this->morphMany(JurnalUmum::class, 'referensi', 'ref_type', 'ref_id');
    }

    /**
     * Hapus entri jurnal terkait dengan model ini.
     *
     * @return void
     */
    public function deleteJournalEntries()
    {
        $this->journalEntries()->delete();
    }
}