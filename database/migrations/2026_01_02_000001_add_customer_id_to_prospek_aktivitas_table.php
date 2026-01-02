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
        Schema::table('prospek_aktivitas', function (Blueprint $table) {
            // Make prospek_id nullable
            $table->foreignId('prospek_id')->nullable()->change();

            // Add customer_id field
            $table->foreignId('customer_id')->nullable()->after('prospek_id')->constrained('customer')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prospek_aktivitas', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');

            // Revert prospek_id to not nullable
            $table->foreignId('prospek_id')->nullable(false)->change();
        });
    }
};
