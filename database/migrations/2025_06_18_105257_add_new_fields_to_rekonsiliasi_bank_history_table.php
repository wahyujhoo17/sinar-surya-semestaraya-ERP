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
        Schema::table('rekonsiliasi_bank_history', function (Blueprint $table) {
            // Check and add fields only if they don't exist
            if (!Schema::hasColumn('rekonsiliasi_bank_history', 'reconciliation_id')) {
                $table->string('reconciliation_id')->after('id')->nullable()->unique();
            }

            if (!Schema::hasColumn('rekonsiliasi_bank_history', 'tahun')) {
                $table->string('tahun', 4)->after('periode')->nullable();
            }

            if (!Schema::hasColumn('rekonsiliasi_bank_history', 'bulan')) {
                $table->string('bulan', 2)->after('tahun')->nullable();
            }

            if (!Schema::hasColumn('rekonsiliasi_bank_history', 'erp_balance')) {
                $table->decimal('erp_balance', 15, 2)->after('bulan')->default(0);
            }

            if (!Schema::hasColumn('rekonsiliasi_bank_history', 'bank_balance')) {
                $table->decimal('bank_balance', 15, 2)->after('erp_balance')->default(0);
            }

            if (!Schema::hasColumn('rekonsiliasi_bank_history', 'status')) {
                $table->enum('status', ['pending', 'balanced', 'reviewed', 'approved'])->after('difference')->default('pending');
            }

            if (!Schema::hasColumn('rekonsiliasi_bank_history', 'matched_transactions')) {
                $table->json('matched_transactions')->after('status')->nullable();
            }

            if (!Schema::hasColumn('rekonsiliasi_bank_history', 'unmatched_erp_transactions')) {
                $table->json('unmatched_erp_transactions')->after('matched_transactions')->nullable();
            }

            if (!Schema::hasColumn('rekonsiliasi_bank_history', 'unmatched_bank_transactions')) {
                $table->json('unmatched_bank_transactions')->after('unmatched_erp_transactions')->nullable();
            }

            if (!Schema::hasColumn('rekonsiliasi_bank_history', 'summary_statistics')) {
                $table->json('summary_statistics')->after('unmatched_bank_transactions')->nullable();
            }

            if (!Schema::hasColumn('rekonsiliasi_bank_history', 'reconciled_by')) {
                $table->string('reconciled_by')->after('summary_statistics')->nullable();
            }

            if (!Schema::hasColumn('rekonsiliasi_bank_history', 'reconciled_at')) {
                $table->timestamp('reconciled_at')->after('reconciled_by')->nullable();
            }

            if (!Schema::hasColumn('rekonsiliasi_bank_history', 'file_uploaded')) {
                $table->string('file_uploaded')->after('reconciled_at')->nullable();
            }

            if (!Schema::hasColumn('rekonsiliasi_bank_history', 'bank_statement_file')) {
                $table->string('bank_statement_file')->after('file_uploaded')->nullable();
            }

            if (!Schema::hasColumn('rekonsiliasi_bank_history', 'notes')) {
                $table->text('notes')->after('bank_statement_file')->nullable();
            }

            if (!Schema::hasColumn('rekonsiliasi_bank_history', 'created_by')) {
                $table->foreignId('created_by')->after('notes')->nullable()->constrained('users');
            }

            if (!Schema::hasColumn('rekonsiliasi_bank_history', 'approved_by')) {
                $table->foreignId('approved_by')->after('created_by')->nullable()->constrained('users');
            }

            if (!Schema::hasColumn('rekonsiliasi_bank_history', 'approved_at')) {
                $table->timestamp('approved_at')->after('approved_by')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekonsiliasi_bank_history', function (Blueprint $table) {
            // Drop new fields if they exist
            $columns = [
                'reconciliation_id',
                'tahun',
                'bulan',
                'erp_balance',
                'bank_balance',
                'status',
                'matched_transactions',
                'unmatched_erp_transactions',
                'unmatched_bank_transactions',
                'summary_statistics',
                'reconciled_by',
                'reconciled_at',
                'file_uploaded',
                'bank_statement_file',
                'notes',
                'created_by',
                'approved_by',
                'approved_at'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('rekonsiliasi_bank_history', $column)) {
                    // Drop foreign key constraints first if applicable
                    if (in_array($column, ['created_by', 'approved_by'])) {
                        $table->dropForeign(['rekening_bank_history_' . $column . '_foreign']);
                    }
                    $table->dropColumn($column);
                }
            }
        });
    }
};
