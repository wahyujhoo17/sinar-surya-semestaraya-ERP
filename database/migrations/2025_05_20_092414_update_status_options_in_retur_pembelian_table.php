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
        // For PostgreSQL, we need to drop existing check constraint and create a new type
        Schema::table('retur_pembelian', function (Blueprint $table) {
            // First, drop the default constraint
            DB::statement("ALTER TABLE retur_pembelian ALTER COLUMN status DROP DEFAULT");

            // Create a new ENUM type if it doesn't exist yet
            DB::statement("DROP TYPE IF EXISTS retur_pembelian_status_enum CASCADE");
            DB::statement("CREATE TYPE retur_pembelian_status_enum AS ENUM('draft', 'diproses', 'menunggu_barang_pengganti', 'selesai')");

            // Change the column type to use the new enum and set the default
            DB::statement("ALTER TABLE retur_pembelian ALTER COLUMN status TYPE retur_pembelian_status_enum USING status::text::retur_pembelian_status_enum");
            DB::statement("ALTER TABLE retur_pembelian ALTER COLUMN status SET DEFAULT 'draft'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // For PostgreSQL, we need to revert back to the previous enum values
        Schema::table('retur_pembelian', function (Blueprint $table) {
            // First, drop the default constraint
            DB::statement("ALTER TABLE retur_pembelian ALTER COLUMN status DROP DEFAULT");

            // Create a new ENUM type for the previous version
            DB::statement("DROP TYPE IF EXISTS retur_pembelian_status_enum_old CASCADE");
            DB::statement("CREATE TYPE retur_pembelian_status_enum_old AS ENUM('draft', 'diproses', 'selesai')");

            // Change the column type to use the previous enum and set the default
            DB::statement("ALTER TABLE retur_pembelian ALTER COLUMN status TYPE retur_pembelian_status_enum_old USING 
                CASE 
                    WHEN status::text = 'menunggu_barang_pengganti' THEN 'diproses'::text
                    ELSE status::text
                END::retur_pembelian_status_enum_old");
            DB::statement("ALTER TABLE retur_pembelian ALTER COLUMN status SET DEFAULT 'draft'");

            // Rename the type to match the original name
            DB::statement("DROP TYPE IF EXISTS retur_pembelian_status_enum CASCADE");
            DB::statement("ALTER TYPE retur_pembelian_status_enum_old RENAME TO retur_pembelian_status_enum");
        });
    }
};
