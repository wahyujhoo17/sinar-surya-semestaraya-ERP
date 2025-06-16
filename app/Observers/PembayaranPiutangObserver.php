<?php

namespace App\Observers;

use App\Models\PembayaranPiutang;
use Illuminate\Support\Facades\Log;

class PembayaranPiutangObserver
{
    /**
     * Handle the PembayaranPiutang "created" event.
     *
     * @param  \App\Models\PembayaranPiutang  $pembayaranPiutang
     * @return void
     */
    public function created(PembayaranPiutang $pembayaranPiutang)
    {
        try {
            $pembayaranPiutang->createAutomaticJournal();
        } catch (\Exception $e) {
            Log::error('Error creating automatic journal for pembayaran piutang: ' . $e->getMessage(), [
                'pembayaran_id' => $pembayaranPiutang->id,
                'exception' => $e
            ]);
        }
    }

    /**
     * Handle the PembayaranPiutang "updated" event.
     *
     * @param  \App\Models\PembayaranPiutang  $pembayaranPiutang
     * @return void
     */
    public function updated(PembayaranPiutang $pembayaranPiutang)
    {
        // If the amount or payment method has changed, recreate the journal
        if ($pembayaranPiutang->isDirty(['jumlah', 'metode_pembayaran'])) {
            try {
                // Delete existing journal entries
                $pembayaranPiutang->deleteJournalEntries();

                // Create new journal entries
                $pembayaranPiutang->createAutomaticJournal();
            } catch (\Exception $e) {
                Log::error('Error updating automatic journal for pembayaran piutang: ' . $e->getMessage(), [
                    'pembayaran_id' => $pembayaranPiutang->id,
                    'exception' => $e
                ]);
            }
        }
    }

    /**
     * Handle the PembayaranPiutang "deleted" event.
     *
     * @param  \App\Models\PembayaranPiutang  $pembayaranPiutang
     * @return void
     */
    public function deleted(PembayaranPiutang $pembayaranPiutang)
    {
        try {
            // Delete associated journal entries
            $pembayaranPiutang->deleteJournalEntries();
        } catch (\Exception $e) {
            Log::error('Error deleting automatic journal for pembayaran piutang: ' . $e->getMessage(), [
                'pembayaran_id' => $pembayaranPiutang->id,
                'exception' => $e
            ]);
        }
    }
}
