<?php

namespace App\Observers;

use App\Models\Invoice;
use Illuminate\Support\Facades\Log;

class InvoiceObserver
{
    /**
     * Handle the Invoice "created" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function created(Invoice $invoice)
    {
        try {
            $invoice->createAutomaticJournal();
        } catch (\Exception $e) {
            Log::error('Error creating automatic journal for invoice: ' . $e->getMessage(), [
                'invoice_id' => $invoice->id,
                'exception' => $e
            ]);
        }
    }

    /**
     * Handle the Invoice "updated" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function updated(Invoice $invoice)
    {
        // If certain fields that affect the journal have changed, recreate the journal
        if ($invoice->isDirty(['subtotal', 'diskon_nominal', 'ppn', 'ongkos_kirim', 'total'])) {
            try {
                // Delete existing journal entries
                $invoice->deleteJournalEntries();

                // Create new journal entries
                $invoice->createAutomaticJournal();
            } catch (\Exception $e) {
                Log::error('Error updating automatic journal for invoice: ' . $e->getMessage(), [
                    'invoice_id' => $invoice->id,
                    'exception' => $e
                ]);
            }
        }
    }

    /**
     * Handle the Invoice "deleted" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function deleted(Invoice $invoice)
    {
        try {
            // Delete associated journal entries
            $invoice->deleteJournalEntries();
        } catch (\Exception $e) {
            Log::error('Error deleting automatic journal for invoice: ' . $e->getMessage(), [
                'invoice_id' => $invoice->id,
                'exception' => $e
            ]);
        }
    }
}
