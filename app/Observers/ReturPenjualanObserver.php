<?php

namespace App\Observers;

use App\Models\ReturPenjualan;
use Illuminate\Support\Facades\Log;

class ReturPenjualanObserver
{
    /**
     * Handle the ReturPenjualan "created" event.
     *
     * @param  \App\Models\ReturPenjualan  $returPenjualan
     * @return void
     */
    public function created(ReturPenjualan $returPenjualan)
    {
        // Only create journals for approved or completed returns
        if (in_array($returPenjualan->status, ['disetujui', 'selesai'])) {
            try {
                $returPenjualan->createAutomaticJournal();
            } catch (\Exception $e) {
                Log::error('Error creating automatic journal for retur penjualan: ' . $e->getMessage(), [
                    'retur_id' => $returPenjualan->id,
                    'exception' => $e
                ]);
            }
        }
    }

    /**
     * Handle the ReturPenjualan "updated" event.
     *
     * @param  \App\Models\ReturPenjualan  $returPenjualan
     * @return void
     */
    public function updated(ReturPenjualan $returPenjualan)
    {
        // If status has changed to approved or completed, create journal
        // If total or status has changed for approved/completed returns, recreate journal
        if ($returPenjualan->isDirty(['status', 'total', 'tipe_retur'])) {
            try {
                // Delete existing journal entries
                $returPenjualan->deleteJournalEntries();

                // If the return is now approved or completed, create new journal entries
                if (in_array($returPenjualan->status, ['disetujui', 'selesai'])) {
                    $returPenjualan->createAutomaticJournal();
                }
            } catch (\Exception $e) {
                Log::error('Error updating automatic journal for retur penjualan: ' . $e->getMessage(), [
                    'retur_id' => $returPenjualan->id,
                    'exception' => $e
                ]);
            }
        }
    }

    /**
     * Handle the ReturPenjualan "deleted" event.
     *
     * @param  \App\Models\ReturPenjualan  $returPenjualan
     * @return void
     */
    public function deleted(ReturPenjualan $returPenjualan)
    {
        try {
            // Delete associated journal entries
            $returPenjualan->deleteJournalEntries();
        } catch (\Exception $e) {
            Log::error('Error deleting automatic journal for retur penjualan: ' . $e->getMessage(), [
                'retur_id' => $returPenjualan->id,
                'exception' => $e
            ]);
        }
    }
}
