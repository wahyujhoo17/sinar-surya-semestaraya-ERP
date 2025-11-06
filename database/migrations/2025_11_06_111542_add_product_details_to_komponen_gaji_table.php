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
        Schema::table('komponen_gaji', function (Blueprint $table) {
            $table->json('product_details')->nullable()->after('komisi_rate');
            $table->decimal('sales_ppn', 5, 2)->nullable()->after('product_details');
            $table->boolean('has_sales_ppn')->default(false)->after('sales_ppn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('komponen_gaji', function (Blueprint $table) {
            $table->dropColumn(['product_details', 'sales_ppn', 'has_sales_ppn']);
        });
    }
};
