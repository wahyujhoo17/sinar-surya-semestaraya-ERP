<?php

namespace App\Observers;

use App\Models\PembayaranHutang;
use Illuminate\Support\Facades\Log;

class PembayaranHutangObserver
{
    /**
     * Handle the PembayaranHutang "created" event.
     *
     * @param  \App\Models\PembayaranHutang  $pembayaranHutang
     * @return void
     */
    public function created(PembayaranHutang $pembayaranHutang)
    {
        try {
            $pembayaranHutang->createAutomaticJournal();
        } catch (\Exception $e) {
            Log::error('Error creating automatic journal for pembayaran hutang: ' . $e->getMessage(), [
                'pembayaran_id' => $pembayaranHutang->id,
                'exception' => $e
            ]);
        }
    }

    /**
     * Handle the PembayaranHutang "updated" event.
     *
     * @param  \App\Models\PembayaranHutang  $pembayaranHutang
     * @return void
     */
    public function updated(PembayaranHutang $pembayaranHutang)
    {
        // If the amount or payment method has changed, recreate the journal
        if ($pembayaranHutang->isDirty(['jumlah', 'metode_pembayaran'])) {
            try {
                // Delete existing journal entries
                $pembayaranHutang->deleteJournalEntries();

                // Create new journal entries
                $pembayaranHutang->createAutomaticJournal();
            } catch (\Exception $e) {
                Log::error('Error updating automatic journal for pembayaran hutang: ' . $e->getMessage(), [
                    'pembayaran_id' => $pembayaranHutang->id,
                    'exception' => $e
                ]);
            }
        }
    }

    /**
     * Handle the PembayaranHutang "deleted" event.
     *
     * @param  \App\Models\PembayaranHutang  $pembayaranHutang
     * @return void
     */
    public function deleted(PembayaranHutang $pembayaranHutang)
    {
        try {
            // Delete associated journal entries
            $pembayaranHutang->deleteJournalEntries();
        } catch (\Exception $e) {
            Log::error('Error deleting automatic journal for pembayaran hutang: ' . $e->getMessage(), [
                'pembayaran_id' => $pembayaranHutang->id,
                'exception' => $e
            ]);
        }
    }
}
