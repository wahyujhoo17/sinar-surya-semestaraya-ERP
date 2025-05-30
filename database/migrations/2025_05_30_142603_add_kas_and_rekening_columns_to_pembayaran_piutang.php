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
        Schema::table('pembayaran_piutang', function (Blueprint $table) {
            // Add kas_id and rekening_bank_id columns if they don't exist
            if (!Schema::hasColumn('pembayaran_piutang', 'kas_id')) {
                $table->foreignId('kas_id')->nullable()->after('user_id')->constrained('kas')->nullOnDelete();
            }

            if (!Schema::hasColumn('pembayaran_piutang', 'rekening_bank_id')) {
                $table->foreignId('rekening_bank_id')->nullable()->after('kas_id')->constrained('rekening_bank')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran_piutang', function (Blueprint $table) {
            // Drop foreign keys and columns if they exist
            if (Schema::hasColumn('pembayaran_piutang', 'kas_id')) {
                $table->dropForeign(['kas_id']);
                $table->dropColumn('kas_id');
            }

            if (Schema::hasColumn('pembayaran_piutang', 'rekening_bank_id')) {
                $table->dropForeign(['rekening_bank_id']);
                $table->dropColumn('rekening_bank_id');
            }
        });
    }
};
