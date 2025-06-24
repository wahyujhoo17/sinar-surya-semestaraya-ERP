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
        Schema::create('rekonsiliasi_bank_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rekening_bank_id')->constrained('rekening_bank')->onDelete('cascade');
            $table->date('periode'); // Format: YYYY-MM-01
            $table->decimal('erp_balance', 15, 2)->default(0);
            $table->decimal('bank_balance', 15, 2)->default(0);
            $table->decimal('difference', 15, 2)->default(0);
            $table->enum('status', ['Reconciled', 'Pending', 'Rejected'])->default('Pending');
            $table->json('matched_transactions')->nullable();
            $table->json('unmatched_erp_transactions')->nullable();
            $table->json('unmatched_bank_transactions')->nullable();
            $table->string('bank_statement_file')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['rekening_bank_id', 'periode']);
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekonsiliasi_bank_history');
    }
};
