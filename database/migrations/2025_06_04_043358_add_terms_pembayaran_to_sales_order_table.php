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
            $table->string('terms_pembayaran')->nullable()->after('syarat_ketentuan');
            $table->integer('terms_pembayaran_hari')->nullable()->after('terms_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_order', function (Blueprint $table) {
            $table->dropColumn('terms_pembayaran');
            $table->dropColumn('terms_pembayaran_hari');
        });
    }
};
