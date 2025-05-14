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
        // For PostgreSQL, we need to drop the constraint first and then create a new one
        // This fixes any issues with the constraint while keeping the values in the database as 'parsial'
        DB::statement("ALTER TABLE penerimaan_barang DROP CONSTRAINT IF EXISTS penerimaan_barang_status_check");
        DB::statement("ALTER TABLE penerimaan_barang ADD CONSTRAINT penerimaan_barang_status_check 
                      CHECK (status::text = ANY (ARRAY['parsial'::character varying, 'selesai'::character varying, 'batal'::character varying]::text[]))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No action needed in down method as we're just fixing the constraint
    }
};