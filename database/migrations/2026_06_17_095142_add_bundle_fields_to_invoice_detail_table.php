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
            if (!Schema::hasColumn('invoice_detail', 'is_bundle_item')) {
                $table->boolean('is_bundle_item')->default(false);
            }
            if (!Schema::hasColumn('invoice_detail', 'bundle_name')) {
                $table->string('bundle_name')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_detail', function (Blueprint $table) {
            $table->dropColumn(['is_bundle_item', 'bundle_name']);
        });
    }
};
