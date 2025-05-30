<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('retur_penjualan', function (Blueprint $table) {
            $table->enum('tipe_retur', ['pengembalian_dana', 'tukar_barang'])->default('pengembalian_dana')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('retur_penjualan', function (Blueprint $table) {
            $table->dropColumn('tipe_retur');
        });
    }
};
