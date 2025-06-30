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
        // Check if status has changed to 'dibayar'
        if ($penggajian->isDirty('status') && $penggajian->status === 'dibayar') {
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
        }

        // If the amount has changed while status is 'dibayar', recreate the journal
        if ($penggajian->isDirty(['total_gaji', 'tanggal_bayar']) && $penggajian->status === 'dibayar') {
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
