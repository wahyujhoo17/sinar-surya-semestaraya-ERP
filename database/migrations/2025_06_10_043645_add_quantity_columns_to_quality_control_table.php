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
        Schema::table('quality_control', function (Blueprint $table) {
            $table->decimal('jumlah_lolos', 10, 2)->after('status')->default(0);
            $table->decimal('jumlah_gagal', 10, 2)->after('jumlah_lolos')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quality_control', function (Blueprint $table) {
            $table->dropColumn(['jumlah_lolos', 'jumlah_gagal']);
        });
    }
};
