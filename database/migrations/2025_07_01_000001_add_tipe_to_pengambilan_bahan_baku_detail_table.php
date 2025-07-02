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
        Schema::table('pengambilan_bahan_baku_detail', function (Blueprint $table) {
            $table->enum('tipe', ['bom', 'manual'])->default('bom')->after('keterangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengambilan_bahan_baku_detail', function (Blueprint $table) {
            $table->dropColumn('tipe');
        });
    }
};
