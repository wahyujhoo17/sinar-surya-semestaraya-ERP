<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('quotation_detail', function (Blueprint $table) {
            $table->string('item_type')->default('produk')->after('quotation_id'); // 'produk' or 'bundle'
            $table->foreignId('bundle_id')->nullable()->after('produk_id')->constrained('product_bundles')->onDelete('cascade');
            $table->boolean('is_bundle_item')->default(false)->after('bundle_id'); // If this detail is part of a bundle breakdown
            $table->foreignId('parent_detail_id')->nullable()->after('is_bundle_item')->constrained('quotation_detail')->onDelete('cascade'); // Reference to bundle detail
        });
    }

    public function down()
    {
        Schema::table('quotation_detail', function (Blueprint $table) {
            $table->dropForeign(['bundle_id']);
            $table->dropForeign(['parent_detail_id']);
            $table->dropColumn(['item_type', 'bundle_id', 'is_bundle_item', 'parent_detail_id']);
        });
    }
};
