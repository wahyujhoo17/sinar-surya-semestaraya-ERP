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
        Schema::table('perencanaan_produksi', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['sales_order_id']);

            // Make the column nullable
            $table->foreignId('sales_order_id')->nullable()->change();

            // Add back the foreign key constraint with nullable
            $table->foreign('sales_order_id')
                ->references('id')
                ->on('sales_order')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perencanaan_produksi', function (Blueprint $table) {
            // Drop the nullable foreign key
            $table->dropForeign(['sales_order_id']);

            // Make it NOT NULL again
            $table->foreignId('sales_order_id')->nullable(false)->change();

            // Add back the original foreign key constraint
            $table->foreign('sales_order_id')
                ->references('id')
                ->on('sales_order')
                ->onDelete('cascade');
        });
    }
};
