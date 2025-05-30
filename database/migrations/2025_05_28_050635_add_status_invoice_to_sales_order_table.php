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
            $table->enum('status_invoice', ['not_invoiced', 'partially_invoiced', 'fully_invoiced'])
                ->default('not_invoiced')
                ->after('status_pembayaran')
                ->comment('Status penagihan: not_invoiced, partially_invoiced, fully_invoiced');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_order', function (Blueprint $table) {
            $table->dropColumn('status_invoice');
        });
    }
};
