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
            $table->string('sumber')->nullable()->after('keterangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jurnal_umum', function (Blueprint $table) {
            $table->dropColumn('sumber');
        });
    }
};
