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
        Schema::table('jurnal_umum', function (Blueprint $table) {
            // Tambah kolom untuk audit trail dan kontrol
            $table->boolean('is_posted')->default(false)->after('jenis_jurnal')
                ->comment('Status posting jurnal');
            $table->timestamp('posted_at')->nullable()->after('is_posted')
                ->comment('Waktu posting jurnal');
            $table->foreignId('posted_by')->nullable()->after('posted_at')
                ->constrained('users')->comment('User yang posting jurnal');
            $table->boolean('is_reversed')->default(false)->after('posted_by')
                ->comment('Status jurnal balik');
            $table->string('reversal_ref')->nullable()->after('is_reversed')
                ->comment('Referensi jurnal balik');
            $table->text('approval_notes')->nullable()->after('reversal_ref')
                ->comment('Catatan persetujuan');

            // Index untuk performa
            $table->index(['is_posted', 'tanggal']);
            $table->index(['jenis_jurnal', 'is_posted']);
            $table->index(['is_reversed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jurnal_umum', function (Blueprint $table) {
            $table->dropForeign(['posted_by']);
            $table->dropIndex(['is_posted', 'tanggal']);
            $table->dropIndex(['jenis_jurnal', 'is_posted']);
            $table->dropIndex(['is_reversed']);

            $table->dropColumn([
                'is_posted',
                'posted_at',
                'posted_by',
                'is_reversed',
                'reversal_ref',
                'approval_notes'
            ]);
        });
    }
};
