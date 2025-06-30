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
        Schema::table('pembayaran_hutang', function (Blueprint $table) {
            $table->unsignedBigInteger('kas_id')->nullable()->after('user_id');
            $table->unsignedBigInteger('rekening_id')->nullable()->after('kas_id');

            // Add foreign key constraints
            $table->foreign('kas_id')->references('id')->on('kas')->onDelete('set null');
            $table->foreign('rekening_id')->references('id')->on('rekening_bank')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran_hutang', function (Blueprint $table) {
            $table->dropForeign(['kas_id']);
            $table->dropForeign(['rekening_id']);
            $table->dropColumn(['kas_id', 'rekening_id']);
        });
    }
};
