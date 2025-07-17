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
            $table->unsignedBigInteger('periode_id')->nullable()->after('tanggal');
            $table->foreign('periode_id')->references('id')->on('periode_akuntansi')->onDelete('set null');
            $table->index(['periode_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jurnal_umum', function (Blueprint $table) {
            $table->dropForeign(['periode_id']);
            $table->dropIndex(['periode_id', 'tanggal']);
            $table->dropColumn('periode_id');
        });
    }
};
