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
        // For PostgreSQL, we need to create a new enum type and then alter the column
        // First drop existing constraints if any
        DB::statement("ALTER TABLE work_order ALTER COLUMN status DROP DEFAULT");

        // Drop any existing type constraint
        DB::statement("
            DO $$ 
            BEGIN
                ALTER TABLE work_order ALTER COLUMN status TYPE VARCHAR(255);
            EXCEPTION
                WHEN others THEN NULL;
            END $$;
        ");

        // Create or replace the enum type
        DB::statement("
            DO $$ 
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'work_order_status_enum') THEN
                    CREATE TYPE work_order_status_enum AS ENUM('direncanakan', 'berjalan', 'selesai_produksi', 'qc_passed', 'pengembalian_material', 'selesai', 'dibatalkan');
                ELSE
                    ALTER TYPE work_order_status_enum ADD VALUE IF NOT EXISTS 'selesai_produksi';
                    ALTER TYPE work_order_status_enum ADD VALUE IF NOT EXISTS 'qc_passed';
                    ALTER TYPE work_order_status_enum ADD VALUE IF NOT EXISTS 'pengembalian_material';
                END IF;
            END $$;
        ");

        // Alter the column to use the new enum type
        DB::statement("ALTER TABLE work_order ALTER COLUMN status TYPE work_order_status_enum USING status::work_order_status_enum");

        // Set the default value
        DB::statement("ALTER TABLE work_order ALTER COLUMN status SET DEFAULT 'direncanakan'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // For PostgreSQL, we need to create a new enum type and then alter the column
        // First drop default
        DB::statement("ALTER TABLE work_order ALTER COLUMN status DROP DEFAULT");

        // Create or replace the enum type with original values
        DB::statement("
            CREATE TYPE work_order_status_enum_old AS ENUM('direncanakan', 'berjalan', 'selesai', 'dibatalkan');
        ");

        // Alter the column to use the new type
        DB::statement("ALTER TABLE work_order ALTER COLUMN status TYPE work_order_status_enum_old USING 
            CASE status::text 
                WHEN 'selesai_produksi' THEN 'berjalan'::work_order_status_enum_old
                WHEN 'qc_passed' THEN 'berjalan'::work_order_status_enum_old
                WHEN 'pengembalian_material' THEN 'berjalan'::work_order_status_enum_old
                ELSE status::text::work_order_status_enum_old
            END
        ");

        // Drop the new type
        DB::statement("
            DROP TYPE IF EXISTS work_order_status_enum;
            ALTER TYPE work_order_status_enum_old RENAME TO work_order_status_enum;
        ");

        // Set the default value back
        DB::statement("ALTER TABLE work_order ALTER COLUMN status SET DEFAULT 'direncanakan'");
    }
};
