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
        Schema::table('komponen_gaji', function (Blueprint $table) {
            $table->decimal('margin_persen', 15, 4)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('komponen_gaji', function (Blueprint $table) {
            $table->decimal('margin_persen', 8, 4)->nullable()->change();
        });
    }
};
