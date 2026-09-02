<?php

namespace App\Observers;

use App\Models\Penggajian;
use Illuminate\Support\Facades\Log;

class PenggajianObserver
{
    /**
     * Handle the Penggajian "updated" event.
     * Create journal entries when status changes to 'dibayar'
     *
     * @param  \App\Models\Penggajian  $penggajian
     * @return void
     */
    public function updated(Penggajian $penggajian)
    {
        $statusChanged = $penggajian->wasChanged('status') || $penggajian->isDirty('status');

        // Check if status has changed to 'dibayar'
        if ($statusChanged && $penggajian->status === 'dibayar') {
            try {
                // Delete any existing journal entries first
                $penggajian->deleteJournalEntries();

                // Create new journal entries
                $penggajian->createAutomaticJournal();
            } catch (\Exception $e) {
                Log::error('Error creating automatic journal for penggajian: ' . $e->getMessage(), [
                    'penggajian_id' => $penggajian->id,
                    'karyawan_id' => $penggajian->karyawan_id,
                    'exception' => $e
                ]);
            }
        } elseif ($statusChanged && $penggajian->getOriginal('status') === 'dibayar' && $penggajian->status !== 'dibayar') {
            // Status was reverted from 'dibayar' to something else (e.g. draft/disetujui)
            try {
                $penggajian->deleteJournalEntries();
            } catch (\Exception $e) {
                Log::error('Error deleting automatic journal on status revert for penggajian: ' . $e->getMessage(), [
                    'penggajian_id' => $penggajian->id,
                    'karyawan_id' => $penggajian->karyawan_id,
                    'exception' => $e
                ]);
            }
        } elseif ($penggajian->status === 'dibayar') {
            // If the amount, payment date, or period (bulan/tahun) has changed while status remains 'dibayar', recreate the journal
            $paymentFields = ['total_gaji', 'thp', 'tanggal_bayar', 'metode_pembayaran', 'kas_id', 'rekening_id', 'bulan', 'tahun'];
            if ($penggajian->wasChanged($paymentFields) || $penggajian->isDirty($paymentFields)) {
                try {
                    // Delete existing journal entries
                    $penggajian->deleteJournalEntries();

                    // Create new journal entries
                    $penggajian->createAutomaticJournal();
                } catch (\Exception $e) {
                    Log::error('Error updating automatic journal for penggajian: ' . $e->getMessage(), [
                        'penggajian_id' => $penggajian->id,
                        'karyawan_id' => $penggajian->karyawan_id,
                        'exception' => $e
                    ]);
                }
            }
        }
    }

    /**
     * Handle the Penggajian "deleted" event.
     *
     * @param  \App\Models\Penggajian  $penggajian
     * @return void
     */
    public function deleted(Penggajian $penggajian)
    {
        try {
            // Delete associated journal entries when penggajian is deleted
            $penggajian->deleteJournalEntries();
        } catch (\Exception $e) {
            Log::error('Error deleting automatic journal for penggajian: ' . $e->getMessage(), [
                'penggajian_id' => $penggajian->id,
                'karyawan_id' => $penggajian->karyawan_id,
                'exception' => $e
            ]);
        }
    }
}
