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
        Schema::table('invoice', function (Blueprint $table) {
            $table->decimal('uang_muka_terapkan', 15, 2)->default(0)->after('total');
            $table->decimal('sisa_tagihan', 15, 2)->after('uang_muka_terapkan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice', function (Blueprint $table) {
            $table->dropColumn(['uang_muka_terapkan', 'sisa_tagihan']);
        });
    }
};
