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
        Schema::table('sales_order', function (Blueprint $table) {
            // Only add fields that don't exist yet
            $table->decimal('total_pembayaran', 15, 2)->default(0)->after('ongkos_kirim');
            $table->decimal('kelebihan_bayar', 15, 2)->default(0)->after('total_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_order', function (Blueprint $table) {
            $table->dropColumn(['total_pembayaran', 'kelebihan_bayar']);
        });
    }
};
