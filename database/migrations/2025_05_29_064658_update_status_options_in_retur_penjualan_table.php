<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, drop the existing check constraint
        DB::statement("ALTER TABLE retur_penjualan DROP CONSTRAINT IF EXISTS retur_penjualan_status_check");

        // Then, add the updated constraint with the new status option
        DB::statement("ALTER TABLE retur_penjualan ADD CONSTRAINT retur_penjualan_status_check 
            CHECK (status IN ('draft', 'menunggu_persetujuan', 'disetujui', 'ditolak', 'diproses', 'menunggu_barang_pengganti', 'selesai'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First, drop the modified constraint
        DB::statement("ALTER TABLE retur_penjualan DROP CONSTRAINT IF EXISTS retur_penjualan_status_check");

        // Then, restore the original constraint without the new status option
        DB::statement("ALTER TABLE retur_penjualan ADD CONSTRAINT retur_penjualan_status_check 
            CHECK (status IN ('draft', 'menunggu_persetujuan', 'disetujui', 'ditolak', 'diproses', 'selesai'))");
    }
};
