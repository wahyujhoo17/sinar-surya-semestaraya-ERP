<?php

namespace App\Observers;

use App\Models\BiayaOperasional;
use Illuminate\Support\Facades\Log;

class BiayaOperasionalObserver
{
    /**
     * Handle the BiayaOperasional "created" event.
     *
     * @param  \App\Models\BiayaOperasional  $biayaOperasional
     * @return void
     */
    public function created(BiayaOperasional $biayaOperasional)
    {
        try {
            $biayaOperasional->createAutomaticJournal();
        } catch (\Exception $e) {
            Log::error('Error creating automatic journal for biaya operasional: ' . $e->getMessage(), [
                'biaya_id' => $biayaOperasional->id,
                'exception' => $e
            ]);
        }
    }

    /**
     * Handle the BiayaOperasional "updated" event.
     *
     * @param  \App\Models\BiayaOperasional  $biayaOperasional
     * @return void
     */
    public function updated(BiayaOperasional $biayaOperasional)
    {
        // If the amount or payment method has changed, recreate the journal
        if ($biayaOperasional->isDirty(['jumlah', 'metode_pembayaran', 'kategori_biaya_id'])) {
            try {
                // Delete existing journal entries
                $biayaOperasional->deleteJournalEntries();

                // Create new journal entries
                $biayaOperasional->createAutomaticJournal();
            } catch (\Exception $e) {
                Log::error('Error updating automatic journal for biaya operasional: ' . $e->getMessage(), [
                    'biaya_id' => $biayaOperasional->id,
                    'exception' => $e
                ]);
            }
        }
    }

    /**
     * Handle the BiayaOperasional "deleted" event.
     *
     * @param  \App\Models\BiayaOperasional  $biayaOperasional
     * @return void
     */
    public function deleted(BiayaOperasional $biayaOperasional)
    {
        try {
            // Delete associated journal entries
            $biayaOperasional->deleteJournalEntries();
        } catch (\Exception $e) {
            Log::error('Error deleting automatic journal for biaya operasional: ' . $e->getMessage(), [
                'biaya_id' => $biayaOperasional->id,
                'exception' => $e
            ]);
        }
    }
}
