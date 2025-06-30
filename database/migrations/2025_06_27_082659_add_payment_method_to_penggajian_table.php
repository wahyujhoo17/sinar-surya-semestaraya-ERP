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
        Schema::table('penggajian', function (Blueprint $table) {
            $table->enum('metode_pembayaran', ['kas', 'bank'])->default('kas')->after('tanggal_bayar');
            $table->unsignedBigInteger('kas_id')->nullable()->after('metode_pembayaran');
            $table->unsignedBigInteger('rekening_id')->nullable()->after('kas_id');

            // Add foreign keys
            $table->foreign('kas_id')->references('id')->on('kas')->onDelete('set null');
            $table->foreign('rekening_id')->references('id')->on('rekening_bank')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penggajian', function (Blueprint $table) {
            $table->dropForeign(['kas_id']);
            $table->dropForeign(['rekening_id']);
            $table->dropColumn(['metode_pembayaran', 'kas_id', 'rekening_id']);
        });
    }
};
