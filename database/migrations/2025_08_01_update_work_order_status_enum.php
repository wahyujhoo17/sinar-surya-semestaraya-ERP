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
        // Change the status enum to include the new states
        DB::statement("ALTER TABLE work_order MODIFY COLUMN status ENUM('direncanakan', 'berjalan', 'selesai_produksi', 'qc_passed', 'pengembalian_material', 'selesai', 'dibatalkan') DEFAULT 'direncanakan'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE work_order MODIFY COLUMN status ENUM('direncanakan', 'berjalan', 'selesai', 'dibatalkan') DEFAULT 'direncanakan'");
    }
};
