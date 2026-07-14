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
        Schema::table('delivery_order', function (Blueprint $table) {
            $table->unsignedBigInteger('sales_order_id')->nullable()->change();
        });

        Schema::table('delivery_order_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('sales_order_detail_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_order', function (Blueprint $table) {
            $table->unsignedBigInteger('sales_order_id')->nullable(false)->change();
        });

        Schema::table('delivery_order_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('sales_order_detail_id')->nullable(false)->change();
        });
    }
};
