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
        Schema::table('periode_akuntansi', function (Blueprint $table) {
            // Add year and month columns for better indexing and querying
            $table->integer('tahun')->nullable()->after('nama');
            $table->integer('bulan')->nullable()->after('tahun');
            $table->text('keterangan')->nullable()->after('catatan_penutupan');

            // Add index for better performance
            $table->index(['tahun', 'bulan']);
            $table->index('tahun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('periode_akuntansi', function (Blueprint $table) {
            $table->dropIndex(['tahun', 'bulan']);
            $table->dropIndex(['tahun']);
            $table->dropColumn(['tahun', 'bulan', 'keterangan']);
        });
    }
};
