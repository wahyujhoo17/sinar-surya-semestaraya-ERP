<?php

namespace App\Observers;

use App\Models\PenyesuaianStok;
use Illuminate\Support\Facades\Log;

class PenyesuaianStokObserver
{
    /**
     * Handle the PenyesuaianStok "created" event.
     *
     * @param  \App\Models\PenyesuaianStok  $penyesuaianStok
     * @return void
     */
    public function created(PenyesuaianStok $penyesuaianStok)
    {
        // Only create journals for approved or completed adjustments
        if (in_array($penyesuaianStok->status, ['disetujui', 'selesai'])) {
            try {
                $penyesuaianStok->createAutomaticJournal();
            } catch (\Exception $e) {
                Log::error('Error creating automatic journal for penyesuaian stok: ' . $e->getMessage(), [
                    'penyesuaian_id' => $penyesuaianStok->id,
                    'exception' => $e
                ]);
            }
        }
    }

    /**
     * Handle the PenyesuaianStok "updated" event.
     *
     * @param  \App\Models\PenyesuaianStok  $penyesuaianStok
     * @return void
     */
    public function updated(PenyesuaianStok $penyesuaianStok)
    {
        // If status has changed to approved or completed, create journal
        if ($penyesuaianStok->isDirty(['status'])) {
            try {
                // Delete existing journal entries
                $penyesuaianStok->deleteJournalEntries();

                // If the adjustment is now approved or completed, create new journal entries
                if (in_array($penyesuaianStok->status, ['disetujui', 'selesai'])) {
                    $penyesuaianStok->createAutomaticJournal();
                }
            } catch (\Exception $e) {
                Log::error('Error updating automatic journal for penyesuaian stok: ' . $e->getMessage(), [
                    'penyesuaian_id' => $penyesuaianStok->id,
                    'exception' => $e
                ]);
            }
        }
    }

    /**
     * Handle the PenyesuaianStok "deleted" event.
     *
     * @param  \App\Models\PenyesuaianStok  $penyesuaianStok
     * @return void
     */
    public function deleted(PenyesuaianStok $penyesuaianStok)
    {
        try {
            // Delete associated journal entries
            $penyesuaianStok->deleteJournalEntries();
        } catch (\Exception $e) {
            Log::error('Error deleting automatic journal for penyesuaian stok: ' . $e->getMessage(), [
                'penyesuaian_id' => $penyesuaianStok->id,
                'exception' => $e
            ]);
        }
    }
}
