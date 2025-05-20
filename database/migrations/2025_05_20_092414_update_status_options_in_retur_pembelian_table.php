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
        // First, get info about any existing check constraints on the status column
        $checkConstraintName = DB::select("
            SELECT con.conname
            FROM pg_constraint con
            INNER JOIN pg_class rel ON rel.oid = con.conrelid
            INNER JOIN pg_namespace nsp ON nsp.oid = rel.relnamespace
            INNER JOIN pg_attribute att ON att.attrelid = rel.oid AND att.attnum = ANY(con.conkey)
            WHERE rel.relname = 'retur_pembelian'
            AND att.attname = 'status'
            AND con.contype = 'c'
        ");

        // Drop any existing check constraint
        if (!empty($checkConstraintName)) {
            $constraintName = $checkConstraintName[0]->conname;
            DB::statement("ALTER TABLE retur_pembelian DROP CONSTRAINT IF EXISTS {$constraintName}");
        }

        // For PostgreSQL, we need to modify the column properly
        Schema::table('retur_pembelian', function (Blueprint $table) {
            // First, drop the default constraint
            DB::statement("ALTER TABLE retur_pembelian ALTER COLUMN status DROP DEFAULT");

            // Convert status to text type temporarily to remove any enum constraints
            DB::statement("ALTER TABLE retur_pembelian ALTER COLUMN status TYPE TEXT");

            // Now add the check constraint to match our enum values
            DB::statement("ALTER TABLE retur_pembelian ADD CONSTRAINT retur_pembelian_status_check 
                CHECK (status IN ('draft', 'diproses', 'menunggu_barang_pengganti', 'selesai'))");

            // Set the default back
            DB::statement("ALTER TABLE retur_pembelian ALTER COLUMN status SET DEFAULT 'draft'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get any existing check constraint on the status column
        $checkConstraintName = DB::select("
            SELECT con.conname
            FROM pg_constraint con
            INNER JOIN pg_class rel ON rel.oid = con.conrelid
            INNER JOIN pg_namespace nsp ON nsp.oid = rel.relnamespace
            INNER JOIN pg_attribute att ON att.attrelid = rel.oid AND att.attnum = ANY(con.conkey)
            WHERE rel.relname = 'retur_pembelian'
            AND att.attname = 'status'
            AND con.contype = 'c'
        ");

        // Drop any existing check constraint
        if (!empty($checkConstraintName)) {
            $constraintName = $checkConstraintName[0]->conname;
            DB::statement("ALTER TABLE retur_pembelian DROP CONSTRAINT IF EXISTS {$constraintName}");
        }

        // For PostgreSQL, revert back to previous check constraint
        Schema::table('retur_pembelian', function (Blueprint $table) {
            // First, drop the default constraint
            DB::statement("ALTER TABLE retur_pembelian ALTER COLUMN status DROP DEFAULT");

            // Update any 'menunggu_barang_pengganti' values to 'diproses'
            DB::statement("UPDATE retur_pembelian SET status = 'diproses' WHERE status = 'menunggu_barang_pengganti'");

            // Add back the original constraint
            DB::statement("ALTER TABLE retur_pembelian ADD CONSTRAINT retur_pembelian_status_check 
                CHECK (status IN ('draft', 'diproses', 'selesai'))");

            // Set default back
            DB::statement("ALTER TABLE retur_pembelian ALTER COLUMN status SET DEFAULT 'draft'");
        });
    }
};
