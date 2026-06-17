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
        Schema::table('invoice_detail', function (Blueprint $table) {
            if (!Schema::hasColumn('invoice_detail', 'item_type')) {
                $table->string('item_type')->default('produk')->after('subtotal');
            }
            if (!Schema::hasColumn('invoice_detail', 'bundle_id')) {
                $table->unsignedBigInteger('bundle_id')->nullable()->after('item_type');
            }
            if (!Schema::hasColumn('invoice_detail', 'parent_detail_id')) {
                $table->unsignedBigInteger('parent_detail_id')->nullable()->after('is_bundle_item');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_detail', function (Blueprint $table) {
            $table->dropColumn(['item_type', 'bundle_id', 'parent_detail_id']);
        });
    }
};
