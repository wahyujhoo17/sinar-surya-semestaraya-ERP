<?php

namespace App\Services;

use App\Models\JurnalUmum;
use App\Models\AkunAkuntansi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JournalEntryService
{
    /**
     * Membuat entri jurnal umum
     *
     * @param array $entries Array berisi entri jurnal yang akan dibuat
     * @param string $noReferensi Nomor referensi untuk entri jurnal
     * @param string $keterangan Keterangan transaksi
     * @param string|null $tanggal Tanggal transaksi, default adalah hari ini
     * @param object|null $reference Object yang terkait dengan entri jurnal
     * @return bool
     */
    public function createJournalEntries(array $entries, string $noReferensi, string $keterangan, ?string $tanggal = null, $reference = null)
    {
        // Validasi bahwa total debit dan kredit harus sama
        $totalDebit = 0;
        $totalKredit = 0;

        foreach ($entries as $entry) {
            $totalDebit += (float) ($entry['debit'] ?? 0);
            $totalKredit += (float) ($entry['kredit'] ?? 0);
        }

        if (abs($totalDebit - $totalKredit) > 0.01) {
            Log::error("Jurnal tidak seimbang. Debit: $totalDebit, Kredit: $totalKredit", [
                'entries' => $entries,
                'noReferensi' => $noReferensi
            ]);
            return false;
        }

        try {
            DB::beginTransaction();

            $tanggal = $tanggal ?? date('Y-m-d');
            $userId = Auth::id() ?? 1; // Default ke admin jika tidak ada user yang login

            // Buat entri jurnal untuk setiap akun yang terlibat
            foreach ($entries as $entry) {
                $journalData = [
                    'tanggal' => $tanggal,
                    'no_referensi' => $noReferensi,
                    'akun_id' => $entry['akun_id'],
                    'debit' => $entry['debit'] ?? 0,
                    'kredit' => $entry['kredit'] ?? 0,
                    'keterangan' => $keterangan,
                    'jenis_jurnal' => 'umum',
                    'user_id' => $userId,
                    'is_posted' => true,
                    'posted_at' => now(),
                    'posted_by' => $userId
                ];

                // Tambahkan referensi jika ada
                if ($reference) {
                    $journalData['ref_type'] = get_class($reference);
                    $journalData['ref_id'] = $reference->id;
                }

                JurnalUmum::create($journalData);
            }

            DB::commit();

            Log::info("Jurnal berhasil dibuat", [
                'noReferensi' => $noReferensi,
                'tanggal' => $tanggal,
                'entryCount' => count($entries)
            ]);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error saat membuat jurnal: " . $e->getMessage(), [
                'exception' => $e,
                'noReferensi' => $noReferensi
            ]);
            return false;
        }
    }

    /**
     * Mendapatkan entri jurnal berdasarkan referensi
     *
     * @param object $reference Object yang terkait dengan entri jurnal
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getJournalEntriesByReference($reference)
    {
        return JurnalUmum::where('ref_type', get_class($reference))
            ->where('ref_id', $reference->id)
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();
    }

    /**
     * Menghapus entri jurnal berdasarkan referensi
     *
     * @param object $reference Object yang terkait dengan entri jurnal
     * @return bool
     */
    public function deleteJournalEntriesByReference($reference)
    {
        try {
            JurnalUmum::where('ref_type', get_class($reference))
                ->where('ref_id', $reference->id)
                ->delete();

            return true;
        } catch (\Exception $e) {
            Log::error("Error saat menghapus jurnal: " . $e->getMessage(), [
                'exception' => $e,
                'reference' => get_class($reference) . ':' . $reference->id
            ]);
            return false;
        }
    }
}
