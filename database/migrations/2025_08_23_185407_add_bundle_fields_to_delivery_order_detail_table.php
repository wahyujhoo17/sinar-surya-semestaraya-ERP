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
        Schema::table('delivery_order_detail', function (Blueprint $table) {
            $table->boolean('is_bundle_item')->default(false)->after('keterangan');
            $table->string('bundle_name')->nullable()->after('is_bundle_item');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_order_detail', function (Blueprint $table) {
            $table->dropColumn(['is_bundle_item', 'bundle_name']);
        });
    }
};
