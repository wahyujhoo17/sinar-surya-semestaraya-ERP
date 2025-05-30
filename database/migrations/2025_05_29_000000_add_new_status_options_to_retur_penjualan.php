<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddNewStatusOptionsToReturPenjualan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // First, we'll check if any retur_penjualan records have the old status values
        // and convert them to match our new status options
        $oldStatuses = DB::table('retur_penjualan')->select('id', 'status')->get();

        foreach ($oldStatuses as $record) {
            $newStatus = $record->status;

            // Map old statuses to new ones if needed
            // Keep existing valid statuses as is
            if (!in_array($newStatus, ['draft', 'menunggu_persetujuan', 'disetujui', 'ditolak', 'diproses', 'selesai'])) {
                // Default mapping for any unexpected values
                $newStatus = 'draft';
            }

            // Update the record with the new status
            DB::table('retur_penjualan')
                ->where('id', $record->id)
                ->update(['status' => $newStatus]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // This migration only updates data, not schema, so the down method
        // could be left empty or implement a reverse mapping if needed
    }
}