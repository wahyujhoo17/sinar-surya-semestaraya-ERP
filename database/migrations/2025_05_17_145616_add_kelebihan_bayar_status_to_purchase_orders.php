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
        // Tidak bisa langsung mengubah tipe enum di dalam database,
        // jadi kita perlu menggunakan raw SQL queries

        // Untuk MySQL/MariaDB, tambahkan nilai enum baru

        // Tambahkan kolom untuk menyimpan jumlah kelebihan bayar
        Schema::table('purchase_order', function (Blueprint $table) {
            $table->decimal('kelebihan_bayar', 15, 2)->default(0)->after('status_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus kolom kelebihan_bayar
        Schema::table('purchase_order', function (Blueprint $table) {
            $table->dropColumn('kelebihan_bayar');
        });
    }
};
