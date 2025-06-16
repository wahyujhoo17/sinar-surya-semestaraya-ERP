<?php

namespace App\Observers;

use App\Models\ReturPembelian;
use Illuminate\Support\Facades\Log;

class ReturPembelianObserver
{
    /**
     * Handle the ReturPembelian "created" event.
     *
     * @param  \App\Models\ReturPembelian  $returPembelian
     * @return void
     */
    public function created(ReturPembelian $returPembelian)
    {
        // Only create journals for processed or completed returns
        if (in_array($returPembelian->status, ['diproses', 'selesai'])) {
            try {
                $returPembelian->createAutomaticJournal();
            } catch (\Exception $e) {
                Log::error('Error creating automatic journal for retur pembelian: ' . $e->getMessage(), [
                    'retur_id' => $returPembelian->id,
                    'exception' => $e
                ]);
            }
        }
    }

    /**
     * Handle the ReturPembelian "updated" event.
     *
     * @param  \App\Models\ReturPembelian  $returPembelian
     * @return void
     */
    public function updated(ReturPembelian $returPembelian)
    {
        // If status has changed to processed or completed, create journal
        // If status or return type has changed for processed/completed returns, recreate journal
        if ($returPembelian->isDirty(['status', 'tipe_retur'])) {
            try {
                // Delete existing journal entries
                $returPembelian->deleteJournalEntries();

                // If the return is now processed or completed, create new journal entries
                if (in_array($returPembelian->status, ['diproses', 'selesai'])) {
                    $returPembelian->createAutomaticJournal();
                }
            } catch (\Exception $e) {
                Log::error('Error updating automatic journal for retur pembelian: ' . $e->getMessage(), [
                    'retur_id' => $returPembelian->id,
                    'exception' => $e
                ]);
            }
        }
    }

    /**
     * Handle the ReturPembelian "deleted" event.
     *
     * @param  \App\Models\ReturPembelian  $returPembelian
     * @return void
     */
    public function deleted(ReturPembelian $returPembelian)
    {
        try {
            // Delete associated journal entries
            $returPembelian->deleteJournalEntries();
        } catch (\Exception $e) {
            Log::error('Error deleting automatic journal for retur pembelian: ' . $e->getMessage(), [
                'retur_id' => $returPembelian->id,
                'exception' => $e
            ]);
        }
    }
}
