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
        // Add quality control fields to retur_penjualan table
        Schema::table('retur_penjualan', function (Blueprint $table) {
            $table->boolean('requires_qc')->default(true)->after('status');
            $table->boolean('qc_passed')->nullable()->after('requires_qc');
            $table->string('qc_notes')->nullable()->after('qc_passed');
            $table->unsignedBigInteger('qc_by_user_id')->nullable()->after('qc_notes');
            $table->timestamp('qc_at')->nullable()->after('qc_by_user_id');
            $table->text('total')->nullable()->after('catatan');
            // Foreign key for QC user
            $table->foreign('qc_by_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });

        // Add quality control fields to retur_penjualan_detail table
        Schema::table('retur_penjualan_detail', function (Blueprint $table) {
            $table->boolean('qc_checked')->default(false)->after('keterangan');
            $table->boolean('qc_passed')->nullable()->after('qc_checked');
            $table->string('qc_notes')->nullable()->after('qc_passed');
            $table->string('defect_type')->nullable()->after('qc_notes');
            $table->json('qc_images')->nullable()->after('defect_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('retur_penjualan', function (Blueprint $table) {
            // Remove foreign key constraint first
            $table->dropForeign(['qc_by_user_id']);

            // Drop columns
            $table->dropColumn([
                'requires_qc',
                'qc_passed',
                'qc_notes',
                'qc_by_user_id',
                'qc_at',
                'total'
            ]);
        });

        Schema::table('retur_penjualan_detail', function (Blueprint $table) {
            $table->dropColumn([
                'qc_checked',
                'qc_passed',
                'qc_notes',
                'defect_type',
                'qc_images'
            ]);
        });
    }
};
