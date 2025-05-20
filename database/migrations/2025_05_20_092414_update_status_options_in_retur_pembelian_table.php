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
        DB::statement("ALTER TABLE retur_pembelian MODIFY COLUMN status ENUM('draft', 'diproses', 'menunggu_barang_pengganti', 'selesai') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE retur_pembelian MODIFY COLUMN status ENUM('draft', 'diproses', 'selesai') DEFAULT 'draft'");
    }
};
