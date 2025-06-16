<?php

namespace App\Observers;

use App\Models\Pembelian;
use Illuminate\Support\Facades\Log;

class PembelianObserver
{
    /**
     * Handle the Pembelian "created" event.
     *
     * @param  \App\Models\Pembelian  $pembelian
     * @return void
     */
    public function created(Pembelian $pembelian)
    {
        try {
            $pembelian->createAutomaticJournal();
        } catch (\Exception $e) {
            Log::error('Error creating automatic journal for pembelian: ' . $e->getMessage(), [
                'pembelian_id' => $pembelian->id,
                'exception' => $e
            ]);
        }
    }

    /**
     * Handle the Pembelian "updated" event.
     *
     * @param  \App\Models\Pembelian  $pembelian
     * @return void
     */
    public function updated(Pembelian $pembelian)
    {
        // If the total has changed, recreate the journal
        if ($pembelian->isDirty(['total'])) {
            try {
                // Delete existing journal entries
                $pembelian->deleteJournalEntries();

                // Create new journal entries
                $pembelian->createAutomaticJournal();
            } catch (\Exception $e) {
                Log::error('Error updating automatic journal for pembelian: ' . $e->getMessage(), [
                    'pembelian_id' => $pembelian->id,
                    'exception' => $e
                ]);
            }
        }
    }

    /**
     * Handle the Pembelian "deleted" event.
     *
     * @param  \App\Models\Pembelian  $pembelian
     * @return void
     */
    public function deleted(Pembelian $pembelian)
    {
        try {
            // Delete associated journal entries
            $pembelian->deleteJournalEntries();
        } catch (\Exception $e) {
            Log::error('Error deleting automatic journal for pembelian: ' . $e->getMessage(), [
                'pembelian_id' => $pembelian->id,
                'exception' => $e
            ]);
        }
    }
}
